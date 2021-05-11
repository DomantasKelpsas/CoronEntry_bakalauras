from keras.models import load_model
import cv2
import numpy as np
import os

CATEGORIES = ["With Mask", "Without Mask"]

model = load_model(r'C:\\Users\\Echo\\Desktop\\_Bakalauras_03\\CoronEntry\\Face_Mask_ML\\mask_detector.h5')

model.compile(loss='binary_crossentropy',
              optimizer='rmsprop',
              metrics=['accuracy'])
i = 0
test_path = r'C:\\Users\\Echo\\Desktop\\_Bakalauras_03\\CoronEntry\\Face_Mask_ML\\mask-dataset\\With Mask\\'
with open("mlpreds.txt", "w") as text_file:
    for imgage in os.listdir(test_path):
        try:
            img_path = os.path.join(test_path, imgage)
            img = cv2.imread(img_path)
            img = cv2.resize(img,(224,224))
            img = np.reshape(img,[1,224,224,3])
            
            pred = model.predict(img)
            pred_acc = pred[0][np.argmax(pred)]
            pred_name = CATEGORIES[np.argmax(pred)]
            print(pred_name, pred_acc)
            #print(pred)       
            text_file.write("pred_name: %s : pred_acc %f\n" % (pred_name, pred_acc))       
        except Exception as e:
            print(str(e))