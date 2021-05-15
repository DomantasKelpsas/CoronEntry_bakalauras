package com.ktu.coronentry

import android.app.PendingIntent.getActivity
import android.content.Intent
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import androidx.appcompat.app.AppCompatActivity
import androidx.core.content.ContextCompat
import kotlinx.android.synthetic.main.activity_body_temp.*
import kotlinx.coroutines.GlobalScope
import kotlinx.coroutines.delay
import kotlinx.coroutines.launch

class BodyTempActivity : AppCompatActivity(), MqttDataInterface {

    val bodyTempTopic = "/domantas.kelpsas@gmail.com/bodytemp/in"
    val bodyTempBoolTopic = "/domantas.kelpsas@gmail.com/bodytempBool/in"
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

    var mqttManager: MqttManager? = null


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_body_temp)

        mqttManager = MqttManager(connectionParams, applicationContext, this)
        mqttManager?.connect()

        GlobalScope.launch {
            delay(1000)
            mqttManager?.subscribe(bodyTempTopic)
            mqttManager?.subscribe(bodyTempBoolTopic)
        }


        //updateBodyTemp()


    }

    private fun updateBodyTemp() {

        var tempBool: String? = ""
        var temp: String? = ""

        GlobalScope.launch {
            Log.d("updateBodyTemp", "scope")
            while (true) {
                Log.d("updateBodyTemp", "while")
                delay(1000)
                Log.d("updateBodyTemp", temp.toString())
                Log.d("updateBodyTempBool", tempBool.toString())
                runOnUiThread(Runnable { // This code will always run on the UI thread, therefore is safe to modify UI elements.
                    tv_bodytempVal.text = temp
                })

                tv_bodytempVal.setTextColor(
                    ContextCompat.getColor(
                        applicationContext,
                        if (tempBool == "true") R.color.green else R.color.red
                    )
                )
            }
        }

    }
    override fun getMqttMesage(topic: String, message: String) {
        Log.d("BodyTemp", "got MQTT interface message -> $message")

            runOnUiThread(Runnable { // This code will always run on the UI thread, therefore is safe to modify UI elements.
                if(topic == bodyTempTopic)
                tv_bodytempVal.text = "$message °C"
                tv_bodytempVal.setTextColor(
                    ContextCompat.getColor(
                        applicationContext,
                        if (topic == bodyTempBoolTopic && message == "true") R.color.green else R.color.red
                    )
                )

            })
        if (topic == bodyTempBoolTopic && message == "true"){
            Toast.makeText(this, " Access Was Succesfully Verified", Toast.LENGTH_LONG).show()
            finish()
        }
    }

    fun mqtttest(view: View) {
        MainActivity().mqttManager?.publish(bodyTempBoolTopic,"true")
    }

    private fun gotoMainActivity(user: String) {
        val intent = Intent(this, MainActivity::class.java).apply {
        }
        intent.putExtra("user", user)
        startActivity(intent)
        finish()
    }

}