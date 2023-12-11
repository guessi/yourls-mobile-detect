#!/bin/bash

KEYWORD="xxx"

# https://whatmyuseragent.com/device/go/google-pixel-8
UA_ANDROID="Mozilla/5.0 (Linux; Android 14; Pixel 8) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/113.0.4430.82 Mobile Safari/537.36"
curl --head localhost/${KEYWORD} -A "${UA_ANDROID}"

# https://whatmyuseragent.com/platforms/ios/ios/17
UA_IOS="Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/113.0.5672.121 Mobile/15E148 Safari/604.1"
curl --head localhost/${KEYWORD} -A "${UA_IOS}"

UA_CURL="curl/8.5.0"
curl --head localhost/${KEYWORD} -A "${UA_CURL}"
