from PIL import Image
import os, sys

path = r"C:\\Users\\Echo\\Documents\\_PRAKTIKA\\ML\\without_flora\\"
dirs = os.listdir( path )

def resize():
    for item in dirs:
        if os.path.isfile(path+item):
            im = Image.open(path+item)
            f, e = os.path.splitext(path+item)
            imResize = im.resize((512,512), Image.ANTIALIAS)
            imResize.save(f + ' resized.jpg', 'JPEG', quality=90)

resize()