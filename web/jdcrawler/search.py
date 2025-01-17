# -*- coding: utf-8 -*-
# import aircv as ac
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
from selenium.webdriver.common.keys import Keys

# from PIL import Image
# import requests
import io
from io import BytesIO
import sys
import urllib.request
from urllib.parse import urlencode
import traceback


class gp(object):
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

        port = random.randint(9515,9517)
        port = str(port)
        port = '9515'
        # self.driver = webdriver.Remote("http://192.168.1.178:"+port+"/wd/hub", options=chrome_option)
        self.driver = webdriver.Chrome(options=chrome_option)

        # 处理selenium中webdriver特征值
        # js="var q=document.documentElement.scrollTop=100000"  
        # self.driver.execute_script(js)  
        

        # self.driver.set_window_size(1440, 900)
        self.driver.maximize_window()

        self.api = 'http://jd.xiaozhumeimeigou.com/api/tiny-shop/v1/jd'

        self.pagecount = 0
        self.maxtopage = 100
        self.currentpage = 0

    def search(self, name, pageurl=''):
        try:
            params = {
                'keyword':name,
                'enc' : 'utf-8',
                'wq' : name,
            }
            url="https://search.jd.com/Search?shop=1&stock=1&"+urlencode(params) # shop=1 京东物流 stock=1 有货
            if pageurl:
                url = pageurl
            
            self.driver.get(url)

            time.sleep(2)

            #点击勾选中京东物流


            time.sleep(3)
            #将滚动条移动到页面的底部
            js="var q=document.documentElement.scrollTop=100000"  
            self.driver.execute_script(js)  
            time.sleep(3)

            # with open("r.html","w",encoding="utf-8") as fd:
            #     fd.write(self.driver.page_source)

            self.pagecount = self.driver.find_element(By.CLASS_NAME,'p-skip').find_element(By.TAG_NAME,'b').get_attribute("textContent")
            print('总页数',self.pagecount)

            self.pagecount = int(self.pagecount)

            if self.maxtopage > self.pagecount:
                self.maxtopage = self.pagecount
            self.currentpage = 1
            # self.driver.save_screenshot("search/"+name+".png")

            self.tiquSku()

            if self.maxtopage > self.currentpage:
                self.xiayiye()
                pass
        finally:
            self.driver.quit()

    def xiayiye(self):
        # //下一页
        self.driver.find_element(By.CLASS_NAME,'pn-next').click()        
        time.sleep(3)    
        #将滚动条移动到页面的底部
        js="var q=document.documentElement.scrollTop=100000"  
        self.driver.execute_script(js)  
        time.sleep(3)
         
        self.tiquSku()

        self.currentpage += 1
        if self.maxtopage > self.currentpage:
            self.xiayiye()


    def tiquSku(self):

        list_dom = self.driver.find_elements(By.XPATH,'//*[@id="J_goodsList"]/ul/li')
        
        result_l = len(list_dom)
        print("列表结果个数",result_l)
        
        aryGreatSku = []
        for li in list_dom:
            sku = li.get_dom_attribute("data-sku")
            i_tips = li.find_elements(By.TAG_NAME,'i')
            
            for tip in i_tips:
                _txt = tip.get_attribute('textContent')
                if _txt.startswith('满'):
                    aryGreatSku.append(sku)
                    break

        print("skus are ",','.join(aryGreatSku))

        if len(aryGreatSku) > 0:
            params = {
                'skus' : ','.join(aryGreatSku),
                'page' : self.currentpage
            }
            urllib.request.urlopen(self.api + "/sku/save?"+ urlencode(params))

# python search.py "化妆品" 
if __name__ == "__main__":
    print(sys.argv)
    name = sys.argv[1]
    print(name)
    h = gp()
    h.search(name)


