# -*- coding: utf-8 -*-
# import aircv as ac
import json
import random
# import six
import os,base64
import time, re
from selenium import webdriver
from selenium.common.exceptions import TimeoutException
from selenium.webdriver.common.by import By
from selenium.webdriver.support.wait import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from selenium.webdriver.common.action_chains import ActionChains
# from PIL import Image
# import requests
import io
from io import BytesIO
import sys
import urllib.request
from urllib.parse import urlencode

# https://www.cnblogs.com/ff-gaofeng/p/12049361.html python+selenium实现经京东登录+购物+支付
class jd(object):
    def __init__(self):
        chrome_option = webdriver.ChromeOptions()
        UserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36'
        chrome_option.add_argument('User-Agent=' + UserAgent)
        # proxy 代理 options 选项
                
        #代理IP池
        # proxy_arr = [
        #     '--proxy-server=http://111.3.118.247:30001',
        #     '--proxy-server=http://183.247.211.50:30001',
        #     '--proxy-server=http://122.9.101.6:8888',
        # ]
        # proxy = random.choice(proxy_arr)  # 随机选择一个代理
        # print(proxy) #如果某个代理访问失败,可从proxy_arr中去除
        # chrome_option.add_argument(proxy)  # 添加代理

        # docker run -tid --name selenium-standalone-chrome -h selenium-standalone-chrome --memory 1g --shm-size="1g" --memory-swap -1 -p 9515:4444 selenium/standalone-chrome
        #self.driver = webdriver.Chrome(executable_path=r"F:\下载\chromedriver.exe", chrome_options=chrome_option)
        
        #chrome_option.add_argument('--no-sandbox')       
        # self.driver = webdriver.Chrome(executable_path=r"C:\Users\Administrator\AppData\Local\MyChrome\Chrome\Application\chromedriver.exe")
        # port = random.randint(9515,9517)
        # port = str(port)
        # self.driver = webdriver.Remote("http://gg.lucktp.com:"+port+"/wd/hub", options=chrome_option)
        # 处理selenium中webdriver特征值
        # self.driver.command_executor(
        #     'Page.addScriptToEvaluateOnNewDocument',
        #     {
        #         'source':'Object.defineProperty(navigator,"webdriver",{get:()=>undefined})'
        #     }
        # )

        self.driver = webdriver.Chrome()
        # self.driver.set_window_size(1440, 900)
        self.driver.maximize_window()

        self.api = 'http://gg.lucktp.com/api/v1'

        self.url = 'https://www.jd.com'

        self.cookie_file = "jd_cookie"

    def check_status(self):
        url = "https://order.jd.com/center/list.action"
        if os.path.exists(self.cookie_file):
            #获取cookies文件
            with open(self.cookie_file,"r") as fp:
                jd_cookies = fp.read()
            #加载cookies信息
            jd_cookies_dict = json.loads(jd_cookies)
            for cookie in jd_cookies_dict:
                self.driver.add_cookie(cookie)
        self.driver.get(url)
        time.sleep(2)
        print(self.driver.current_url)
        if self.driver.current_url.startswith("https://passport.jd.com"):
            self.driver.find_element(By.XPATH,"//div[@class='qrcode-img']").screenshot("login_qr.png")
            #等待扫码登录
            #urllib.request.urlopen(self.api + "/jd/qr?name=zrf") #发送扫码提醒
            time.sleep(30)
            #获取cookie
            my_cookie = self.driver.get_cookies()
            print(my_cookie)
            data_cookie = json.dumps(my_cookie)
            with open(self.cookie_file,"w") as fp:
                fp.write(data_cookie)


    def xd(self, name):
        try:
            url="https://play.google.com/store/search?q="+name+"&c=apps&gl=us"
            
            self.driver.get(url)

            time.sleep(2)
            #self.driver.save_screenshot("search/"+name+".png")
            self.driver.find_element(By.TAG_NAME,'html').screenshot("search/"+name+".png")
            
            list_dom = self.driver.find_elements(By.XPATH,'//a[@class="Si6A0c Gy4nib"]')
            
            result_l = len(list_dom)
            print("列表结果个数",result_l)

            if result_l == 0:
                _t = random.random()*10 + 3
                print('休息'+str(_t)+'秒后继续重试')
                time.sleep(_t)
                self.search(name)
                return

            position = 0
            for a in list_dom:
                href = a.get_dom_attribute('href')
                if href.count('id=') == 0:
                    continue
                
                package_name = href.split('id=')[1]
                position += 1
                
                a.screenshot("icon/" + package_name + '.png')


                appname = a.find_element(By.CLASS_NAME,'DdYX5').get_attribute('textContent')
                company = a.find_element(By.CLASS_NAME,'wMUdtb').get_attribute('textContent') #wMUdtb
                try:
                    star = a.find_element(By.CLASS_NAME,'w2kbF').get_attribute('textContent')#w2kbF
                except:
                    star = 0

                # params = {
                #     'name' : appname,
                #     'link_name' : name,
                #     'package_name' : package_name,
                #     'company': company,
                #     'is_down' : 0,
                #     'had_notify' : 0,
                #     'star' : star,
                #     'position' : position,
                # }

                # print(params)
                # urllib.request.urlopen(self.api + "/package/save?" + urlencode(params))

            #返回主框架
            self.driver.switch_to.default_content()
        except Exception as e:
            print('异常信息')
            print(e)
        finally:
            self.driver.quit()

# python google_play.py whatsapp 
if __name__ == "__main__":
    h = jd()
    h.check_status()


