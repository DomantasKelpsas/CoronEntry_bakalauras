from tflite_support.metadata_writers import image_classifier
from tflite_support.metadata_writers import writer_utils
from tflite_support import metadata

ObjectDetectorWriter = image_classifier.MetadataWriter
_MODEL_PATH = r"c:\\Users\\Echo/Desktop\\Face_Mask_Project\\mask_detector.tflite"
_LABEL_FILE = r"c:\\Users\\Echo/Desktop\\Face_Mask_Project\\labels.txt"
_SAVE_TO_PATH = r"c:\\Users\\Echo/Desktop\\Face_Mask_Project\\face_mask_detector.tflite"

writer = ObjectDetectorWriter.create_for_inference(
    writer_utils.load_file(_MODEL_PATH), [127.5], [127.5], [_LABEL_FILE])
writer_utils.save_file(writer.populate(), _SAVE_TO_PATH)

# Verify the populated metadata and associated files.
displayer = metadata.MetadataDisplayer.with_model_file(_SAVE_TO_PATH)
print("Metadata populated:")
print(displayer.get_metadata_json())
print("Associated file(s) populated:")
print(displayer.get_packed_associated_file_list())