package com.ktu.coronentry

import android.content.Intent
import androidx.appcompat.app.AppCompatActivity
import android.os.Bundle
import android.util.Log
import android.view.View
import android.widget.Toast
import kotlinx.android.synthetic.main.activity_register.*

class RegisterActivity : AppCompatActivity(), MqttDataInterface {

    val outTopic = "/domantas.kelpsas@gmail.com/register-creds/out"
    val inTopic = "/domantas.kelpsas@gmail.com/register-creds/in"

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

    var name: String = ""
    var email: String = ""
    var password: String = ""
    var password_repeat: String = ""

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_register)

        mqttManager = MqttManager(connectionParams, applicationContext, this)
        mqttManager?.connect()

        btn_register.setOnClickListener {
            name = et_name.text.trim().toString()
            email = et_email.text.trim().toString()
            password = et_password.text.trim().toString()
            password_repeat = et_password_repeat.text.trim().toString()

            initRegister(name, email, password, password_repeat)
        }
    }

    override fun getMqttMesage(topic: String, message: String) {
        Log.d("Main", "got MQTT interface message -> $message")
        if (topic == inTopic) {
            if (message != "false") {
                gotoMainActivity(message)
            } else if (message == "false") Toast.makeText(
                this,
                "Email is already used",
                Toast.LENGTH_LONG
            ).show()

        }
    }


    fun gotoLoginActivity(view: View) {
        val intent = Intent(this, LoginActivity::class.java).apply {
        }
        startActivity(intent)
        finish()
    }

    private fun initRegister(
        name: String,
        email: String,
        password: String,
        password_repeat: String
    ) {

        if (!(name.isEmpty() || email.isEmpty() || password.isEmpty() || password_repeat.isEmpty())) {
            if (email.isEmailValid()) {
                if (checkPasswordCorrect(password, password_repeat))
                    sendRegisterCreds(name, email, password)
                else Toast.makeText(this, "Password doesn't match", Toast.LENGTH_LONG).show()
            }
            else Toast.makeText(this, "Email is invalid", Toast.LENGTH_LONG).show()
        } else Toast.makeText(this, "Please fill all fields", Toast.LENGTH_LONG).show()
    }

    private fun checkPasswordCorrect(password: String, password_repeat: String): Boolean {
        return password == password_repeat
    }

    private fun sendRegisterCreds(name: String, email: String, password: String) {
        var registerCreds =
            "{\"name\": \"$name\",\"email\": \"$email\", \"password\": \"$password\"}"
        mqttManager?.publish(outTopic, registerCreds)
        mqttManager?.subscribe(inTopic)
    }

    private fun gotoMainActivity(user: String) {
        val intent = Intent(this, MainActivity::class.java).apply {
        }
        intent.putExtra("user", user)
        startActivity(intent)
        finish()
    }
    fun String.isEmailValid(): Boolean {
        return android.util.Patterns.EMAIL_ADDRESS.matcher(this).matches()
    }


}