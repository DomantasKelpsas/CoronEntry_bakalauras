package com.ktu.coronentry

//import android.graphics.Camera
//import org.tensorflow.lite.schema.Model

import android.Manifest
import android.bluetooth.*
import android.bluetooth.le.*
import android.content.Intent
import android.content.pm.PackageManager
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.camera.core.*
import androidx.core.app.ActivityCompat
import androidx.core.content.ContextCompat
import androidx.fragment.app.FragmentActivity
import kotlinx.android.synthetic.main.activity_main.*
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch
import org.eclipse.paho.client.mqttv3.IMqttActionListener
import org.eclipse.paho.client.mqttv3.IMqttToken
import java.util.*
import org.json.JSONException
import org.json.JSONObject

//, MqttDataInterface
class MainActivity : AppCompatActivity(), MqttDataInterface {


    var mqttManager: MqttManager? = null
    val outTopic = "/domantas.kelpsas@gmail.com/con-creds/out"
    val inTopic = "/domantas.kelpsas@gmail.com/con-creds/in"
    val logoutTopic = "/domantas.kelpsas@gmail.com/logout"
    val mqttBroker = "mqtt.dioty.co";
    val mqttUser = "domantas.kelpsas@gmail.com";
    val mqttPassword = "8b2ae255";
    var host = "tcp://$mqttBroker:1883"

    var connectionParams = MQTTConnectionParams(
        "CoronEntryApp",
        host,
        mqttUser,
        mqttPassword
    )

    private var connectCreds = ""


    companion object {
        private const val TAG = "Main Act"
        private const val REQUEST_CODE_PERMISSIONS = 0x98
        private val REQUIRED_PERMISSIONS: Array<String> = arrayOf(
            Manifest.permission.CAMERA, Manifest.permission.ACCESS_FINE_LOCATION,
            Manifest.permission.WRITE_EXTERNAL_STORAGE
        )

    }


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)

        mqttManager = MqttManager(connectionParams, applicationContext, this)
        mqttManager?.connect()

        if (!allPermissionsGranted) {
            requireCameraPermission()
        } else {

        }

        val user = intent.getStringExtra("user")
        val userObj = JSONObject(user)
        val user_code = userObj.getString("user_code")
        tv_usercode.text = "User Code: #$user_code"
        val placeid = userObj.getString("placeid")
        Log.d("JsonParse", "$user_code (id)-> $placeid")
        connectCreds = "{\"user_code\": \"$user_code\","

        if (placeid == null || placeid == "null") {
            btnEpConnect.isEnabled = false
            btnEpConnect.isClickable = false
        } else {
            btnEpConnect.isEnabled = true
            btnEpConnect.isClickable = true
        }
    }


    private fun requireCameraPermission() {
        ActivityCompat.requestPermissions(this, REQUIRED_PERMISSIONS, REQUEST_CODE_PERMISSIONS)
    }


    private fun grantedCameraPermission(requestCode: Int) {
        if (requestCode == REQUEST_CODE_PERMISSIONS) {
            if (allPermissionsGranted) {

            } else {
                Toast.makeText(this, "PERMISION NOT GRANTED", Toast.LENGTH_LONG).show()
            }
            finish()
        }
    }


    private val allPermissionsGranted: Boolean
        get() {
            return REQUIRED_PERMISSIONS.all {
                ContextCompat.checkSelfPermission(
                    baseContext,
                    it
                ) == PackageManager.PERMISSION_GRANTED
            }
        }


    override fun onRequestPermissionsResult(
        requestCode: Int,
        permissions: Array<String>,
        grantResults: IntArray
    ) {
        grantedCameraPermission(requestCode)
    }

    fun epConnect(view: View) {
        Log.d("epConnect()", "clicked")
        gotoQrScanActivity()
    }

    fun logout(view: View) {
        Log.d("logout", "clicked")
        mqttManager?.publish(logoutTopic, "true")
        gotoLoginActivity()

    }

    override fun getMqttMesage(topic: String, message: String) {
        Log.d("Main", "got MQTT interface message -> $message")
        if (message == "true" && topic == inTopic) {

        }
    }

    private fun gotoQrScanActivity() {
        val intent = Intent(this, QrScanActivity::class.java).apply {
        }
        intent.putExtra("connectCreds", connectCreds)
        startActivity(intent)
    }

    private fun gotoLoginActivity() {
        val intent = Intent(this, LoginActivity::class.java).apply {
        }
        startActivity(intent)
        finish()
    }

}


