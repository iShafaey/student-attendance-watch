import os
import platform
import time
import random
import requests
from selenium import webdriver
from selenium.webdriver.common.keys import Keys
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options

# إنشاء خيارات المتصفح
options = Options()

# تحديد مسار user-data بناءً على نظام التشغيل
user_data_path = os.path.join(os.getenv("LOCALAPPDATA"), "Google", "Chrome", "User Data")

# تعيين خيارات المستخدم
options.add_experimental_option("excludeSwitches", ["enable-logging"])
# options.add_argument("--headless")
options.add_argument("--timeout=60")
options.add_argument(f"--user-data-dir={user_data_path}")
options.add_argument("--profile-directory=Default")  # إذا كنت ترغب في استخدام الملف الشخصي الافتراضي

# إعداد متصفح Chrome باستخدام webdriver_manager
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=options)

# تعريف API URLs
api_url = 'http://127.0.0.1:8000/api/v1.0/attendances/students/get-numbers'
update_api_url = 'http://127.0.0.1:8000/api/v1.0/attendances/students/update-status'

# دالة لتحديث حالة الرقم بعد إرسال الرسالة بنجاح أو فشل
def update_status(phone_number, status):
    response = requests.post(update_api_url, json={'phone_number': phone_number, 'status': status})
    return response.status_code == 200

# دالة لإرسال رسالة عبر WhatsApp Web
def send_whatsapp_message(phone_number, message):
    print(f"Started send message to {phone_number}")
    try:
        # فتح الرابط مع رقم الهاتف
        whatsapp_url = f"https://web.whatsapp.com/send?phone={phone_number}&text={message}"
        driver.get(whatsapp_url)

        time.sleep(20)  # انتظار تحميل WhatsApp Web

        # انتظار ظهور زر الإرسال والنقر عليه
        send_button = driver.find_element(By.XPATH, '//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[2]/button')
        send_button.click()

        time.sleep(5)
        return True

    except Exception as e:
        print(f"Failed to send message to {phone_number}: {e}")
        return False

# حلقة لانهائية لتنفيذ الاستعلام كل 20 ثانية
while True:
    try:
        # الاستعلام عن الأرقام من API
        response = requests.get(api_url)

        if response.status_code == 200:
            data = response.json()
            contacts = data.get('contacts', [])  # نفترض أن الـ API يعيد قائمة من الكائنات التي تحتوي على الرقم والاسم
            message_template = data.get('message')  # الحصول على نص الرسالة من الـ API

            # تكرار الأرقام وإرسال الرسائل
            for contact in contacts:
                phone_number = contact.get('phone_number')
                name = contact.get('name')

                # تخصيص الرسالة باستخدام الاسم
                message = message_template.replace("{name}", name)  # استبدال {name} بالاسم الفعلي

                if send_whatsapp_message(phone_number, message):
                    print(f"Message sent to {name} at {phone_number}")

                    # تحديث الحالة في API بعد الإرسال بنجاح
                    if update_status(phone_number, 'message_sent'):
                        print(f"Status updated for {phone_number} (message sent)")
                    else:
                        print(f"Failed to update status for {phone_number}")
                else:
                    print(f"Failed to send message to {name} at {phone_number}")

                    # تحديث الحالة في API بعد فشل الإرسال
                    if update_status(phone_number, 'message_failed'):
                        print(f"Status updated for {phone_number} (message failed)")
                    else:
                        print(f"Failed to update failure status for {phone_number}")

                # إضافة وقت آمن بين الرسائل
                wait_time = random.randint(int(data.get('delay_min')), int(data.get('delay_max')))  # فترة عشوائية بين 60 و180 ثانية
                print(f"Waiting for {wait_time} seconds before sending the next message...")
                time.sleep(wait_time)  # الانتظار قبل إرسال الرسالة التالية

        else:
            print("Failed to retrieve phone numbers")

    except Exception as e:
        print(f"Error occurred: {e}")

    # الانتظار لمدة 60 ثانية قبل التحقق مرة أخرى
    time.sleep(60)
