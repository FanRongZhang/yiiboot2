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

        # chrome_option.add_experimental_option("mobileEmulation", {
        #     "deviceMetrics": {
        #         "width": 375, 
        #         "height": 667, 
        #         "pixelRatio": 2.0,
        #         'deviceName':'Galaxy S5'
        #     },
        #     "userAgent": "Mozilla/5.0 (Linux; U; Android 4.0.4; en-gb; GT-I9300 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30"
        # })

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

        self.api = 'http://jd.xiaozhumeimeigou.com/api/tiny-shop/v1/jd'

        self.url = 'https://www.jd.com'

        self.cookie_file = "jd_cookie"

    def load_cookie(self):
        if os.path.exists(self.cookie_file):
            #获取cookies文件
            with open(self.cookie_file,"r") as fp:
                jd_cookies = fp.read()
            #加载cookies信息
            jd_cookies_dict = json.loads(jd_cookies)
            for cookie in jd_cookies_dict:
                self.driver.add_cookie(cookie)
    
    def save_cookie(self):
        #获取cookie
        my_cookie = self.driver.get_cookies()
        print(my_cookie)
        data_cookie = json.dumps(my_cookie)
        with open(self.cookie_file,"w") as fp:
            fp.write(data_cookie)


    def check_status(self):
        self.load_cookie()
        url = "https://order.jd.com/center/list.action"
        self.driver.get(url)

        time.sleep(5)
        print(self.driver.current_url)

        if self.driver.current_url.startswith("https://passport.jd.com"):
            self.driver.find_element(By.XPATH,"//div[@class='qrcode-img']").screenshot("login_qr.png")

            urllib.request.urlopen(self.api + "/account/login-notify?name=zrf") #发送扫码提醒
        
            #等待扫码登录看是否已成功登录
            while True:
                time.sleep(5)
                #应该会自动返回到目标页
                if self.driver.current_url.startswith("https://order.jd.com/center/list.action"):
                    self.save_cookie()

    def xiadan(self,goods_id):
        url="https://item.jd.com/"+goods_id+".html"
        self.driver.get(url)

        # 点击加入购物车
        self.driver.find_element_by_xpath("//div[@class='itemInfo-wrap']/div/div/a[contains(@onclick,'加入购物车')]").click()
        # 调用driver的page_source属性获取页面源码
        pageSource = self.driver.page_source

        # 断言页面源码中是否包含“商品已成功加入购物车”关键字，以此判断页面内容是否正确
        assert "商品已成功加入购物车" in pageSource

        print("商品已成功加入购物车")

        # 点击“我的购物车”
        self.driver.find_element_by_xpath("//a[text()='我的购物车']").click()
        time.sleep(5)

        # 点击“去结算”button
        # self.driver.find_element_by_xpath("//div[@id='cart-floatbar']/div/div/div/div[2]/div[4]/div[1]/div/div[1]").click()

        # time.sleep(5)

        # 点击“提交订单”button
        # self.driver.find_element_by_xpath("//button[@id='order-submit']").click()

        # 调用driver的page_source属性获取页面源码
        # pageSource = self.driver.page_source

        # 断言页面源码中是否包含“商品已成功加入购物车”关键字，以此判断页面内容是否正确
        # assert "订单提交成功，请尽快付款" in pageSource
        
    def google(self, name):
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

# python xiadan.py 192821 
if __name__ == "__main__":
    goods_id = sys.argv[1]
    h = jd()
    h.check_status()
    h.xiadan(goods_id=goods_id)


