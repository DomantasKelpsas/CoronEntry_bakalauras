package com.ktu.coronentry

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_login.*

class LoginActivity : AppCompatActivity(), MqttDataInterface {

    val outTopic = "/domantas.kelpsas@gmail.com/login-creds/out"
    val inTopic = "/domantas.kelpsas@gmail.com/login-creds/in"

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

    private var email: String? = null
    private var password: String? = null
    private var user_code: String? = null


    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_login)

        mqttManager = MqttManager(connectionParams, applicationContext, this)
        mqttManager?.connect()

        btn_login.setOnClickListener {
            email = et_email.text.trim().toString()
            password = et_password.text.trim().toString()

            sendLoginCreds(email!!,password!!)
        }
    }

    override fun getMqttMesage(topic: String, message: String) {
        Log.d("Main", "got MQTT interface message -> $message")
        if (topic == inTopic) {
            if (message != "false") {
                gotoMainActivity(message)
            } else if (message == "false") Toast.makeText(this, "Email or password is invalid", Toast.LENGTH_LONG).show()

        }
    }

    private fun gotoMainActivity(user_code: String) {
        val intent = Intent(this, MainActivity::class.java).apply {
        }
        intent.putExtra("user_code", user_code)
        startActivity(intent)
        finish()
    }
    fun gotoRegisterActivity(view: View) {
        val intent = Intent(this, RegisterActivity::class.java).apply {
        }
        startActivity(intent)
        finish()
    }
    private fun sendLoginCreds(email: String,password:String){
        var loginCreds = "{\"email\": \"$email\", \"password\": \"$password\"}"
        mqttManager?.publish(outTopic, loginCreds)
        mqttManager?.subscribe(inTopic)
    }
}