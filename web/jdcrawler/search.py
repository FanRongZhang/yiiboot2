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
        self.driver = webdriver.Remote("http://192.168.1.178:"+port+"/wd/hub", options=chrome_option)
        self.driver.set_window_size(1440, 900)

        self.api = 'http://192.168.1.178/api/v1'

    def search(self, name):
        try:
            params = {
                'keyword':name,
                'enc' : 'utf-8',
                'wq' : name,
            }
            url="https://search.jd.com/Search?"+urlencode(params)
            
            self.driver.get(url)

            time.sleep(2)
            self.driver.save_screenshot("search/"+name+".png")

            fd = open("r.html","w")
            fd.write(self.driver.page_source)
            fd.close()

            list_dom = self.driver.find_elements(By.XPATH,'//*[@id="J_goodsList"]/ul/li')
            
            result_l = len(list_dom)
            print("列表结果个数",result_l)

            return

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

                params = {
                    'name' : appname,
                    'link_name' : name,
                    'package_name' : package_name,
                    'company': company,
                    'is_down' : 0,
                    'had_notify' : 0,
                    'star' : star,
                    'position' : position,
                }

                print(params)
                urllib.request.urlopen(self.api + "/package/save?" + urlencode(params))

            #返回主框架
            self.driver.switch_to.default_content()
        except Exception as e:
            print('异常信息')
            print(e)
            print(traceback.format_exc())
            #unable to connect
            #unknown error
            #page crash
            #net::ERR_CONNECTION_REFUSED
            # 包含这个文字应该重试
        finally:
            self.driver.quit()

    def check(self, package_name):
        try:
            url="https://play.google.com/store/apps/details?id="+package_name
            
            self.driver.get(url)

            # button = WebDriverWait(self.driver,3).until(EC.element_to_be_clickable ((By.XPATH,'//*[@id="qqLoginTab"]')))
            # button.click() #点击登录
            time.sleep(2)

            self.driver.save_screenshot("detail/"+package_name+".png")
            
            try:#可正常找到错误提示，说明已经下架
                dom = self.driver.find_element(By.XPATH,'//div[@id="error-section"]')
                is_down = 1
            except:
                is_down = 0
            
            params = {
                'package_name' : package_name,
                'is_down': is_down,
            }

            #详情数据
            try:
                download = self.driver.find_element(By.CLASS_NAME,'wVqUob').get_attribute('textContent')
            except:
                download = '-'
            try:
                contact = self.driver.find_element(By.ID,'developer-contacts').get_attribute('innerHTML')
            except:
                contact = '-'
            try:
                desc = self.driver.find_element(By.CLASS_NAME,'bARER').get_attribute('innerHTML')
            except:
                desc = '-'
            try:
                update_time = self.driver.find_element(By.CLASS_NAME,'TKjAsc').get_attribute('textContent')
            except:
                update_time = '-'
            
            params['download'] = download
            params['contact'] = contact
            params['desc'] = desc
            params['update_time'] = update_time

            urllib.request.urlopen(self.api + "/package/save?"+ urlencode(params))
            

        except Exception as e:
            print(e)    
        finally:
            self.driver.quit()


# python search.py "满减 化妆品" 
if __name__ == "__main__":
    print(sys.argv)
    name = sys.argv[1]
    print(name)
    h = gp()
    h.search(name)



