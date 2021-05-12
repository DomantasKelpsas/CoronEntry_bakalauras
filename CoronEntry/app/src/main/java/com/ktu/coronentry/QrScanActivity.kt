package com.ktu.coronentry

import android.R.attr
import android.app.Activity
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.view.isInvisible
import com.google.zxing.integration.android.IntentIntegrator
import androidx.core.view.isVisible
import kotlinx.android.synthetic.main.activity_qr_scan.*


class QrScanActivity : AppCompatActivity(),MqttDataInterface {

    val outTopic = "/domantas.kelpsas@gmail.com/con-creds/out"
    val inTopic = "/domantas.kelpsas@gmail.com/con-creds/in"
    var mqttManager: MqttManager? = null
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

    private var connectCreds: String? = null

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_qr_scan)
        layout_qr.isVisible = true

        mqttManager = MqttManager(connectionParams, applicationContext, this)
        mqttManager?.connect()

        connectCreds = intent.getStringExtra("connectCreds")

        val scanner = IntentIntegrator(this)
        scanner.setDesiredBarcodeFormats(IntentIntegrator.QR_CODE)
        scanner.setBeepEnabled(false)
        scanner.initiateScan()

        layout_qr.isInvisible = true
    }

    override fun onActivityResult(requestCode: Int, resultCode: Int, data: Intent?) {
        if (resultCode == Activity.RESULT_OK) {
            val result = IntentIntegrator.parseActivityResult(requestCode, resultCode, data)
            if (result != null) {
                if (result.contents == null) {
                    Toast.makeText(this, "Cancelled", Toast.LENGTH_LONG).show()
                } else {
                    connectCredsCheck(result.contents)
                }
            } else {
                super.onActivityResult(requestCode, resultCode, data)
            }

        }
    }

    override fun getMqttMesage(topic: String, message: String) {
        Log.d("qrscan", "got MQTT interface message -> $message")
        if (message == "true" && topic == inTopic) {
            gotoFaceMaskActivity()
        }
        else if(message == "false" && topic == inTopic){
            layout_qr.isVisible = true
        }
    }

    private fun connectCredsCheck(ep_code: String){
        connectCreds += "\"ep_code\": \"$ep_code\"}"
        mqttManager?.publish(outTopic, connectCreds.toString())
        mqttManager?.subscribe(inTopic)
    }
    private fun gotoFaceMaskActivity() {
        val intent = Intent(this, FaceMaskActivity::class.java).apply {
        }
        startActivity(intent)
    }

}