import tensorflow as tf

model = tf.keras.models.load_model( r"C:\\Users\\Echo\Desktop\\Face_Mask_Project\\mask_detector.h5")
converter = tf.lite.TFLiteConverter.from_keras_model(model) # Your model's name
converter.experimental_new_converter = True
model = converter.convert()
file = open( 'mask_detector.tflite' , 'wb' ) 
file.write( model )