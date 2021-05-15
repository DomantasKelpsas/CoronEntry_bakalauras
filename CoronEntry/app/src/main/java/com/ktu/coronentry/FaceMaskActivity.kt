package com.ktu.coronentry

import android.Manifest
import android.annotation.SuppressLint
import android.content.Context
import android.content.Intent
import android.content.res.Configuration
import android.graphics.Bitmap
import android.graphics.Matrix
import android.media.Image
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.DisplayMetrics
import android.util.Log
import androidx.appcompat.content.res.AppCompatResources
import androidx.camera.core.*
import androidx.camera.lifecycle.ProcessCameraProvider
import androidx.core.content.ContextCompat
import androidx.lifecycle.lifecycleScope
import com.google.common.util.concurrent.ListenableFuture
//import com.ktu.coronentry.ml.FackMaskDetection
import com.ktu.coronentry.ml.FaceMaskDetector
import kotlinx.android.synthetic.main.activity_facemask.*
import kotlinx.android.synthetic.main.activity_main.*
import kotlinx.coroutines.Dispatchers
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch
import org.tensorflow.lite.support.image.TensorImage
import org.tensorflow.lite.support.image.TensorImage.fromBitmap
import org.tensorflow.lite.support.label.Category
import org.tensorflow.lite.support.model.Model
import org.tensorflow.lite.support.tensorbuffer.TensorBuffer
import java.util.concurrent.ExecutorService
import java.util.concurrent.Executors
import kotlin.math.abs
import kotlin.math.max
import kotlin.math.min

typealias CameraBitmapOutputListener = (bitmap: Bitmap) -> Unit

class FaceMaskActivity : AppCompatActivity(), MqttDataInterface {

    val mqttBroker = "mqtt.dioty.co";
    val mqttUser = "domantas.kelpsas@gmail.com";
    val mqttPassword = "8b2ae255";
    var host = "tcp://$mqttBroker:1883"
    val topic = "/domantas.kelpsas@gmail.com/mask/out"
    var message_sent = false

    var connectionParams = MQTTConnectionParams(
        "CoronEntryApp",
        host,
        mqttUser,
        mqttPassword
    )

    var mqttManager: MqttManager? = null

    private var preview: Preview? = null
    private var imageAnalyzer: ImageAnalysis? = null
    private var lensFacing: Int = CameraSelector.LENS_FACING_FRONT
    private var camera: Camera? = null
    private var cameraProvider: ProcessCameraProvider? = null
    private lateinit var cameraExecutor: ExecutorService

    private var maskCounter=0

    companion object {
        private const val TAG = "Mask Detection"
        private const val RATIO_4_3_VALUE: Double = 4.0 / 3.0
        private const val RATIO_16_9_VALUE: Double = 16.0 / 9.0
    }

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_facemask)

        SetupML()
        SetupCameraThread()
        SetupCameraControllers()
        setupCamera()

        mqttManager = MqttManager(connectionParams, applicationContext, this)
        mqttManager?.connect()
    }


    //private lateinit var faceMaskDetection: FackMaskDetection
    private lateinit var faceMaskDetection: FaceMaskDetector

    private fun SetupML() {
        val options: Model.Options =
            Model.Options.Builder().setDevice(Model.Device.GPU).setNumThreads(5).build()
        //faceMaskDetection = FackMaskDetection.newInstance(applicationContext, options)
        faceMaskDetection = FaceMaskDetector.newInstance(applicationContext, options)
    }

    private fun SetupCameraControllers() {
        fun setLensButtonIcon() {
            btn_camera_lens_face.setImageDrawable(
                AppCompatResources.getDrawable(
                    applicationContext,
                    if (lensFacing == CameraSelector.LENS_FACING_FRONT)
                        R.drawable.ic_baseline_camera_rear_24
                    else
                        R.drawable.ic_baseline_camera_front_24
                )
            )
        }

        setLensButtonIcon()

        btn_camera_lens_face.setOnClickListener {
            lensFacing = if (CameraSelector.LENS_FACING_FRONT == lensFacing) {
                CameraSelector.LENS_FACING_BACK
            } else {
                CameraSelector.LENS_FACING_FRONT
            }

            setLensButtonIcon()
            setupCameraUseCase()
        }
        try {
            btn_camera_lens_face.isEnabled = hasBackCamera && hasFrontCamera
        } catch (exception: CameraInfoUnavailableException) {
            btn_camera_lens_face.isEnabled = false
        }
    }

    private fun setupCameraUseCase() {
        val cameraSelector: CameraSelector =
            CameraSelector.Builder().requireLensFacing(lensFacing).build()

        val metrics: DisplayMetrics =
            DisplayMetrics().also { preview_view.display.getRealMetrics(it) }
        val rotation: Int = preview_view.display.rotation
        val screenAspectRatio: Int = aspectRatio(metrics.widthPixels, metrics.heightPixels)
        preview = Preview.Builder()
            .setTargetAspectRatio(screenAspectRatio)
            .setTargetRotation(rotation)
            .build()

        imageAnalyzer = ImageAnalysis.Builder()
            .setTargetAspectRatio(screenAspectRatio)
            .setTargetRotation(rotation)
            .build()
            .also {
                it.setAnalyzer(cameraExecutor, BitmapOutPutAnalysis(applicationContext) { bitmap ->
                    setupMLOutput(bitmap)
                })

            }
        cameraProvider?.unbindAll()
        try {
            camera = cameraProvider?.bindToLifecycle(
                this, cameraSelector, preview, imageAnalyzer
            )
            preview?.setSurfaceProvider(preview_view.createSurfaceProvider())
        } catch (exception: Exception) {
            Log.e(TAG, "use case binding failure", exception)
        }
    }

    private fun setupMLOutput(bitmap: Bitmap) {
        val tensorImage: TensorImage = TensorImage.fromBitmap(bitmap)
        //val result: FackMaskDetection.Outputs = faceMaskDetection.process(tensorImage)
        val result: FaceMaskDetector.Outputs = faceMaskDetection.process(tensorImage)
        val output: List<Category> = result.probabilityAsCategoryList.apply {
            sortByDescending { res -> res.score }
        }
        lifecycleScope.launch(Dispatchers.Main) {
            delay(1000)
            if(!message_sent )
            output.firstOrNull()?.let { category ->
                Log.v(TAG, category.score.toString())
                tv_output.text = category.label
                tv_output.setTextColor(
                    ContextCompat.getColor(
                        applicationContext,
                        if (category.label == "With Mask" && category.score > 0.95) {
                            R.color.green
                        } else R.color.red
                    )
                )
                overlay.background =
                    getDrawable(if (category.label == "With Mask" && category.score > 0.95) R.drawable.green_border else R.drawable.red_border)
                pb_output.progressTintList = AppCompatResources.getColorStateList(
                    applicationContext,
                    if (category.label == "With Mask" && category.score > 0.95) R.color.green else R.color.red
                )
                pb_output.progress = (category.score * 100).toInt()
                // kaukes aptikimas
                if (category.label == "With Mask") {
                    maskCounter++
                    if (!message_sent && maskCounter >= 25) {
                        message_sent = true
                        delay(1000)
                        Log.d(TAG, "Mask detected!")
                        //mqttApi?.sendMessage("true")
                        mqttManager?.publish(topic,"true")
                        gotoBodyTempActivity()
                        finish()
                    }

                }
                else maskCounter = 0
                // -----------------------------
            }
        }
    }


    private fun SetupCameraThread() {
        cameraExecutor = Executors.newSingleThreadExecutor()
    }

    private fun setupCamera() {
        val cameraProviderFuture: ListenableFuture<ProcessCameraProvider> =
            ProcessCameraProvider.getInstance(this)

        cameraProviderFuture.addListener(Runnable {
            cameraProvider = cameraProviderFuture.get()
            lensFacing = when {
                hasFrontCamera -> CameraSelector.LENS_FACING_FRONT
                hasBackCamera -> CameraSelector.LENS_FACING_BACK
                else -> throw  IllegalStateException("No cameras found")
            }
            SetupCameraControllers()
            setupCameraUseCase()
        }, ContextCompat.getMainExecutor(this))
    }

    private val hasBackCamera: Boolean
        get() {
            return cameraProvider?.hasCamera(CameraSelector.DEFAULT_BACK_CAMERA) ?: false
        }
    private val hasFrontCamera: Boolean
        get() {
            return cameraProvider?.hasCamera(CameraSelector.DEFAULT_FRONT_CAMERA) ?: false
        }

    override fun onConfigurationChanged(newConfig: Configuration) {
        super.onConfigurationChanged(newConfig)
        SetupCameraControllers()
    }

    private fun aspectRatio(width: Int, height: Int): Int {
        val previewRatio: Double = max(width, height).toDouble() / min(width, height)
        if (abs(previewRatio - RATIO_4_3_VALUE) <= abs(previewRatio - RATIO_16_9_VALUE)) {
            return AspectRatio.RATIO_4_3
        }
        return AspectRatio.RATIO_16_9
    }

    private fun gotoBodyTempActivity() {
        val intent = Intent(this, BodyTempActivity::class.java).apply {
        }
        startActivity(intent)
    }
    override fun getMqttMesage(topic: String, message: String) {
        Log.d("Facemask", "got MQTT interface message -> $message")
    }

}

private class BitmapOutPutAnalysis(
    context: Context,
    private val listener: CameraBitmapOutputListener
) :
    ImageAnalysis.Analyzer {
    private val toRGBConverter = ToRGBConverter(context)
    private lateinit var bitmapBuffer: Bitmap
    private lateinit var rotationMatrix: Matrix

    @SuppressLint("UnsafeExperimentalUsageError")
    private fun ImageProxy.toBitmap(): Bitmap? {
        val image: Image = this.image ?: return null
        if (!::bitmapBuffer.isInitialized) {
            rotationMatrix = Matrix()
            rotationMatrix.postRotate(this.imageInfo.rotationDegrees.toFloat())
            bitmapBuffer = Bitmap.createBitmap(this.width, this.height, Bitmap.Config.ARGB_8888)
        }
        toRGBConverter.convToRGB(image, bitmapBuffer)
        return Bitmap.createBitmap(
            bitmapBuffer,
            0,
            0,
            bitmapBuffer.width,
            bitmapBuffer.height,
            rotationMatrix,
            false
        )
    }

    override fun analyze(imageProxy: ImageProxy) {
        imageProxy.toBitmap()?.let {
            listener(it)
        }
        imageProxy.close()
    }
}

