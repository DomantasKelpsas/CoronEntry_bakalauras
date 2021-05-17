#include <WiFi.h>
#include <PubSubClient.h>
#include <Wire.h>
#include <ESP32Servo.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#define ONE_WIRE_BUS 4

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

const String EP_code = "ep01";

const int ledGreen = 22;
const int ledRed = 23;
const int tempPin = 35;

Servo myservo1;
Servo myservo2;
const int buzzer = 14;
const int servoPin1 = 13;
const int servoPin2 = 12;

float Celcius = 0;

const char* ssid = "POCO";
const char* password = "19216801";

const char* mqtt_broker_user = "domantas.kelpsas@gmail.com";
const char* mqtt_broker_password = "8b2ae255";
const char* mqtt_broker = "mqtt.dioty.co";
const char* outTopic = "/domantas.kelpsas@gmail.com/out";
const char* inTopic = "/domantas.kelpsas@gmail.com/in";
const char* servoTopic = "/domantas.kelpsas@gmail.com/servo";
const char* bodytempTopic = "/domantas.kelpsas@gmail.com/bodytemp/in";
const char* bodytempBoolTopic = "/domantas.kelpsas@gmail.com/bodytempBool/in";
const char* maskTopic = "/domantas.kelpsas@gmail.com/mask/out";
const char* templimitTopic = "/domantas.kelpsas@gmail.com/templimit";
const char* bodytempBoolVirusTopic = "/domantas.kelpsas@gmail.com/bodytempvirus";

bool maskOn = false;
float templimit = 30;
long tempReadingStart = millis();
bool bodyTempReadStarted = false;

WiFiClient espClient;
PubSubClient client(espClient);
long lastMsg = 0;
char msg[50];
int value = 0;


void setup() {

  pinMode(buzzer, OUTPUT);
  pinMode(ledGreen, OUTPUT);
  pinMode(ledRed, OUTPUT);
  pinMode(tempPin, INPUT);

  digitalWrite(ledRed, HIGH);
  digitalWrite(ledGreen, LOW);

  Serial.begin(115200);
  sensors.begin();
  setup_wifi();
  client.setServer(mqtt_broker, 1883);
  client.setCallback(callback);

  myservo1.setPeriodHertz(50);
  myservo1.attach(servoPin1);

  myservo2.setPeriodHertz(50);
  myservo2.attach(servoPin2);
  servoReset();
}

void setup_wifi() {
  delay(10);
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());
}

void callback(char* topic, byte* message, unsigned int length) {
  Serial.print("Message arrived on topic: ");
  Serial.print(topic);
  Serial.print(". Message: ");
  String messageTemp;

  for (int i = 0; i < length; i++) {
    Serial.print((char)message[i]);
    messageTemp += (char)message[i];
  }
  Serial.println();

  if (String(topic) == String(maskTopic)) {
    if (messageTemp == "true") maskOn = true;
  }
  if (String(topic) == String(servoTopic)) {
    servoControl(messageTemp);
  }
  if (String(topic) == String(templimitTopic)) {
    setBodyTempLimit(messageTemp);
  }
}

void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Attempt to connect
    if (client.connect("ESP32", mqtt_broker_user, mqtt_broker_password)) {
      Serial.println("connected");
      // Subscribe
      client.subscribe(inTopic);
      client.subscribe(servoTopic);
      client.subscribe(maskTopic);
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");

      delay(5000);
    }
  }
}

void servoControl(String messageTemp) {
  if (messageTemp == "true")
  {
    digitalWrite(ledGreen, HIGH);
    digitalWrite(ledRed, LOW);
    for (int pos = 0; pos < 130; pos += 1) {
      // in steps of 1 degree
      myservo1.write(pos);
      myservo2.write(pos);
      delay(30);
    }
  }
  else {

    digitalWrite(ledRed, HIGH);
    digitalWrite(ledGreen, LOW);

    for (int pos = 130; pos > 0; pos -= 1) {
      // in steps of 1 degree
      myservo1.write(pos);
      myservo2.write(pos);
      delay(30);
    }
  }

}

void servoReset()
{
  myservo1.write(0);
  myservo2.write(0);
}

float readBodytemp() {
  long now = millis();
  if (!bodyTempReadStarted) {
    tempReadingStart = millis();
    bodyTempReadStarted = true;
  }
  if (now - tempReadingStart > 10000) {
    client.publish(bodytempBoolTopic, "false");
    maskOn = false;
    bodyTempReadStarted = false;
  }
  sensors.requestTemperatures();
  Celcius = sensors.getTempCByIndex(0);
  Serial.print(" C  ");
  Serial.print(Celcius);
  Serial.println("");
  snprintf(msg, 75, "%0.2f", Celcius);
  client.publish(bodytempTopic, msg);
  return Celcius;
}

void setBodyTempLimit(String templimitMsg) {
  templimit = templimitMsg.toFloat();
}


void loop() {


  if (!client.connected()) {
    reconnect();
  }
  client.loop();

  long now = millis();
  if (now - lastMsg > 2000) {

  }
  if (maskOn) {

    float bodytemp = readBodytemp();

    if (bodytemp > templimit)
    {
      client.publish(bodytempBoolVirusTopic, "true");
      client.publish(bodytempBoolTopic, "false");
      maskOn = false;
    }
    if (bodytemp <= templimit && bodytemp >= 25) {
      client.publish(bodytempBoolTopic, "true");
      tone(buzzer, 1000); // Send 1KHz sound signal...
      delay(1000);        // ...for 1 sec
      noTone(buzzer);     // Stop sound...
      maskOn = false;
    }
    delay(1000);
  }


}
