#include <WiFi.h>
#include <PubSubClient.h>
#include <Wire.h>
#include <ESP32Servo.h>
#include <OneWire.h>
#include <DallasTemperature.h>
#define ONE_WIRE_BUS 4

OneWire oneWire(ONE_WIRE_BUS);
DallasTemperature sensors(&oneWire);

const String EP_code = ep03;

const int ledGreen = 22;
const int ledRed = 23;
const int tempPin = 35;

Servo myservo1;
Servo myservo2;
const int buzzer = 14;
const int servoPin1 = 13;
const int servoPin2 = 12;

float Celcius=0;

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

bool maskOn = false;

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
  
  Serial.begin(115200);
  sensors.begin();
  setup_wifi();
  client.setServer(mqtt_broker, 1883);
  client.setCallback(callback);

  myservo1.setPeriodHertz(50); 
  myservo1.attach(servoPin1);
  //myservo1.write(45);

  myservo2.setPeriodHertz(50); 
  myservo2.attach(servoPin2);
  servoReset();
  //myservo2.write(45);

  //servoReset();
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

  if(String(topic) == String(maskTopic)){
    if(messageTemp == "true") maskOn = true;
    }
  if (String(topic) == String(servoTopic)) {
  servoControl(messageTemp);
  }
}

void reconnect() {
  // Loop until we're reconnected
  while (!client.connected()) {
    Serial.print("Attempting MQTT connection...");
    // Attempt to connect
    if (client.connect("ESP32",mqtt_broker_user,mqtt_broker_password)) {
      Serial.println("connected");
      // Subscribe
      client.subscribe(inTopic);
      client.subscribe(servoTopic);
      client.subscribe(maskTopic);
    } else {
      Serial.print("failed, rc=");
      Serial.print(client.state());
      Serial.println(" try again in 5 seconds");
      // Wait 5 seconds before retrying
      delay(5000);
    }
  }
}

void servoControl(String messageTemp){
    if(messageTemp == "true")
      {
      digitalWrite(ledGreen, HIGH);  
      digitalWrite(ledRed, LOW);            
          for(int pos = 0; pos < 130; pos += 1){  // goes from 0 degrees to 180 degrees 
                                    // in steps of 1 degree 
          myservo1.write(pos);              // tell servo to go to position in variable 'pos' 
          myservo2.write(pos);              // tell servo to go to position in variable 'pos' 
          delay(30);
          }
        }
    else {

      digitalWrite(ledRed, HIGH);   
      digitalWrite(ledGreen, LOW);   
      
      for(int pos = 130; pos > 0; pos -= 1){  // goes from 0 degrees to 180 degrees 
                                    // in steps of 1 degree 
    myservo1.write(pos);              // tell servo to go to position in variable 'pos' 
    myservo2.write(pos);              // tell servo to go to position in variable 'pos' 
    delay(30);
   }
      }
       
  }

void servoReset()
{
  myservo1.write(0);              // tell servo to go to position in variable 'pos' 
  myservo2.write(0);              // tell servo to go to position in variable 'pos' 
}

float readBodytemp(){
  sensors.requestTemperatures(); 
  Celcius=sensors.getTempCByIndex(0); 
  Serial.print(" C  ");
  Serial.print(Celcius);  
  Serial.println("");
  snprintf(msg,75,"%0.2f",Celcius);
  client.publish(bodytempTopic,msg);  
  return Celcius;
  }
void loop() {

  //readTemp();
  
  if (!client.connected()) {
    reconnect();
  }
  client.loop();

  long now = millis();
  if (now - lastMsg > 2000) {  

  }
  if(maskOn){

  float bodytemp = readBodytemp();  
  if(bodytemp < 36 && bodytemp > 25){
    client.publish(bodytempBoolTopic,"true");
    tone(buzzer, 1000); // Send 1KHz sound signal...
    delay(1000);        // ...for 1 sec
    noTone(buzzer);     // Stop sound...
    maskOn = false;
    }
  delay(1000);
  }
  
    
}
