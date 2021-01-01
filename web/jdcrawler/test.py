# -*- coding: utf-8 -*-
# import aircv as ac
import json
import random
from selenium import webdriver

chrome_option = webdriver.ChromeOptions()
UserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36'
chrome_option.add_argument('User-Agent=' + UserAgent)

driver = webdriver.Chrome(options=chrome_option)

driver.get("http://localhost")

#获取cookie
my_cookie = driver.get_cookies()
data_cookie = json.dumps(my_cookie)
print(data_cookie)

