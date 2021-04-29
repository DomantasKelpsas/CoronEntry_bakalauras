package com.ktu.coronentry

import android.content.Context
import android.content.Intent
import android.util.Log
import android.view.View
import androidx.lifecycle.MutableLiveData
import kotlinx.coroutines.*
import org.eclipse.paho.android.service.MqttAndroidClient
import org.eclipse.paho.client.mqttv3.*
import java.util.*
import java.util.concurrent.TimeUnit


class MqttManager(
    val connectionParams: MQTTConnectionParams,
    val context: Context,
    val mqttDataInterface: MqttDataInterface
) {

    private var client = MqttAndroidClient(
        context, connectionParams.host, connectionParams.clientId + id(
            context
        )
    )



    private var uniqueID:String? = null
    private val PREF_UNIQUE_ID = "PREF_UNIQUE_ID"
    private var token : IMqttToken? =null


    init {

        client.setCallback(object : MqttCallbackExtended {
            override fun connectComplete(b: Boolean, s: String) {
                Log.w("mqtt", s)

            }

            override fun connectionLost(throwable: Throwable) {

            }

            override fun messageArrived(topic: String, mqttMessage: MqttMessage) {
                Log.w("Mqtt", mqttMessage.toString())
                mqttDataInterface?.getMqttMesage(topic.toString(),mqttMessage.toString())

            }

            override fun deliveryComplete(iMqttDeliveryToken: IMqttDeliveryToken) {
            }
        })
    }
    fun connect(){
        val mqttConnectOptions = MqttConnectOptions()
        mqttConnectOptions.setAutomaticReconnect(true)
        mqttConnectOptions.setCleanSession(true)
        mqttConnectOptions.setUserName(this.connectionParams.username)
        mqttConnectOptions.setPassword(this.connectionParams.password.toCharArray())

        try
        {
            var params = this.connectionParams
           token = client.connect(mqttConnectOptions, null, object : IMqttActionListener {
                override fun onSuccess(asyncActionToken: IMqttToken) {
                    val disconnectedBufferOptions = DisconnectedBufferOptions()
                    disconnectedBufferOptions.setBufferEnabled(true)
                    disconnectedBufferOptions.setBufferSize(100)
                    disconnectedBufferOptions.setPersistBuffer(false)
                    disconnectedBufferOptions.setDeleteOldestMessages(false)
                    client.setBufferOpts(disconnectedBufferOptions)
                    //var subscribeTopic = params.topic + "/in"
                    //subscribe(subscribeTopic)

                }

                override fun onFailure(asyncActionToken: IMqttToken, exception: Throwable) {
                    Log.w("Mqtt", "Failed to connect to: " + params.host + exception.toString())
                }
            })
        }
        catch (ex: MqttException) {
            ex.printStackTrace()
        }

    }

    fun disconnect(){
        try {
            client.disconnect(null, object : IMqttActionListener {
                /**
                 * This method is invoked when an action has completed successfully.
                 * @param asyncActionToken associated with the action that has completed
                 */
                override fun onSuccess(asyncActionToken: IMqttToken?) {

                }

                /**
                 * This method is invoked when an action fails.
                 * If a client is disconnected while an action is in progress
                 * onFailure will be called. For connections
                 * that use cleanSession set to false, any QoS 1 and 2 messages that
                 * are in the process of being delivered will be delivered to the requested
                 * quality of service next time the client connects.
                 * @param asyncActionToken associated with the action that has failed
                 */
                override fun onFailure(asyncActionToken: IMqttToken?, exception: Throwable?) {

                }

            })
        }
        catch (ex: MqttException) {
            System.err.println("Exception disconnect")
            ex.printStackTrace()
        }
    }

    // Subscribe to topic
    fun subscribe(topic: String){
        try
        {

            client.subscribe(topic, 0, null, object : IMqttActionListener {
                override fun onSuccess(asyncActionToken: IMqttToken) {
                    Log.w("Mqtt", "Subscription!")

                }

                override fun onFailure(asyncActionToken: IMqttToken, exception: Throwable) {
                    Log.w("Mqtt", "Subscription fail!")

                }
            })
        }
        catch (ex: MqttException) {
            System.err.println("Exception subscribing")
            ex.printStackTrace()
        }
    }

    // Unsubscribe the topic
    fun unsubscribe(topic: String){

        try
        {
            client.unsubscribe(topic, null, object : IMqttActionListener {
                override fun onSuccess(asyncActionToken: IMqttToken?) {

                }

                override fun onFailure(asyncActionToken: IMqttToken?, exception: Throwable?) {

                }

            })
        }
        catch (ex: MqttException) {
            System.err.println("Exception unsubscribe")
            ex.printStackTrace()
        }

    }

    fun publish(topic: String,message: String){

        try
        {
            var publishTopic = topic
            client.publish(publishTopic,message.toByteArray(),0,false,null,object :IMqttActionListener{
                override fun onSuccess(asyncActionToken: IMqttToken?) {
                    Log.w("Mqtt", "Publish Success!")

                }

                override fun onFailure(asyncActionToken: IMqttToken?, exception: Throwable?) {
                    Log.w("Mqtt", "Publish Failed!")

                }

            })
        }
        catch (ex:MqttException) {
            System.err.println("Exception publishing")
            ex.printStackTrace()
        }



//        token?.setActionCallback(object : IMqttActionListener {
//            override fun onSuccess(asyncActionToken: IMqttToken) {
//
//                try
//                {
//                    client.publish(connectionParams.topic,message.toByteArray(),0,false,null,object :IMqttActionListener{
//                        override fun onSuccess(asyncActionToken: IMqttToken?) {
//                            Log.w("Mqtt", "Publish Success!")
//
//                        }
//
//                        override fun onFailure(asyncActionToken: IMqttToken?, exception: Throwable?) {
//                            Log.w("Mqtt", "Publish Failed!")
//
//                        }
//
//                    })
//                }
//                catch (ex:MqttException) {
//                    System.err.println("Exception publishing")
//                    ex.printStackTrace()
//                }
//
//            }
//
//            override fun onFailure(asyncActionToken: IMqttToken, exception: Throwable) {
//                // Something went wrong e.g. connection timeout or firewall problems
//                Log.d("Publish-mqtt", "onFailure")
//            }
//        })



    }

    @Synchronized fun id(context: Context):String {
        if (uniqueID == null)
        {
            val sharedPrefs = context.getSharedPreferences(
                PREF_UNIQUE_ID, Context.MODE_PRIVATE
            )
            uniqueID = sharedPrefs.getString(PREF_UNIQUE_ID, null)
            if (uniqueID == null)
            {
                uniqueID = UUID.randomUUID().toString()
                val editor = sharedPrefs.edit()
                editor.putString(PREF_UNIQUE_ID, uniqueID)
                editor.commit()
            }
        }
        return uniqueID!!
    }

}

data class MQTTConnectionParams(
    val clientId: String,
    val host: String,
    //val topic: String,
    val username: String,
    val password: String
){

}

//public class MqttApi(val context: Context) : MqttDataInterface{
//
//    var mqttManager: MqttManager? = null
//    val mqttBroker = "mqtt.dioty.co";
//    val mqttUser = "domantas.kelpsas@gmail.com";
//    val mqttPassword = "8b2ae255";
//    var mqttMessage: String = ""
//
//
//
//
//
//    fun connect(topic: String) {
//
//
//        var host = "tcp://$mqttBroker:1883"
//        //var topic = "/domantas.kelpsas@gmail.com/con-creds"
//        var connectionParams = MQTTConnectionParams(
//            "CoronEntryApp",
//            host,
//            topic,
//            mqttUser,
//            mqttPassword
//        )
//        mqttManager = MqttManager(connectionParams, context, this)
//        mqttManager?.connect()
//
//
//    }
//    fun sendMessage(message: String) {
//        mqttManager?.publish(message)
//
//    }
//
//    override fun getMqttMesage(topic: String,message: String){
//        Log.d("MqttApi", "got MQTT interface message -> $message")
//        mqttMessage = message
//
//    }
//    fun getMqttMesage() :String{
//        return mqttMessage
//    }
//    fun disconect(){
//        mqttManager?.disconnect()
//    }
//}

interface MqttDataInterface {

    fun getMqttMesage(topic: String,message: String)
}