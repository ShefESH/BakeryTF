import os
from base64 import b64encode

secret_key = "TAPcKVt+ZyiBSNeD4BCjtg=="

def gen_key():
    random_bytes = os.urandom(16)
    key = b64encode(random_bytes).decode('utf-8')
    print(key)

if __name__ == "__main__":
    gen_key()