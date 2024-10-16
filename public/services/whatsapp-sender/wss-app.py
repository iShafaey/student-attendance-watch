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

# Create browser options
options = Options()

# Define the user-data path based on the operating system
user_data_path = os.path.join(os.getenv("LOCALAPPDATA"), "Google", "Chrome", "User Data")

# Set user options
options.add_experimental_option("excludeSwitches", ["enable-logging"])
# options.add_argument("--headless")
options.add_argument("--timeout=60")
options.add_argument(f"--user-data-dir={user_data_path}")
options.add_argument("--profile-directory=Default")  # Use the default profile if desired

# Set up Chrome browser using webdriver_manager
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=options)

# Define API URLs
api_url = 'http://127.0.0.1:8000/api/v1.0/attendances/students/get-numbers'
update_api_url = 'http://127.0.0.1:8000/api/v1.0/attendances/students/update-status'

# Function to update the status after sending the message successfully or failing
def update_status(phone_number, status, type):
    response = requests.post(update_api_url, json={'phone_number': phone_number, 'status': status, 'type': type})
    return response.status_code == 200

# Function to send a message via WhatsApp Web
def send_whatsapp_message(phone_number, message):
    print(f"Started sending message to {phone_number}")
    try:
        # Open the link with the phone number
        whatsapp_url = f"https://web.whatsapp.com/send?phone={phone_number}&text={message}"
        driver.get(whatsapp_url)

        time.sleep(20)  # Wait for WhatsApp Web to load

        # Wait for the send button to appear and click it
        send_button = driver.find_element(By.XPATH, '//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[2]/button')
        send_button.click()

        time.sleep(5)
        return True

    except Exception as e:
        print(f"Failed to send message to {phone_number}: {e}")
        return False

# Infinite loop to query every 60 seconds
while True:
    try:
        # Query the numbers from the API
        response = requests.get(api_url)

        if response.status_code == 200:
            data = response.json()
            contacts = data.get('contacts', [])  # Assuming the API returns a list of objects containing phone numbers and names

            # Iterate over the numbers and send messages
            for contact in contacts:
                phone_number = contact.get('phone_number')
                name = contact.get('name')
                message = contact.get('message')
                type = contact.get('type')

                if send_whatsapp_message(phone_number, message):
                    print(f"Message sent to {name} at {phone_number}")

                    # Update the status in the API after successful sending
                    if update_status(phone_number, 'message_sent', type):
                        print(f"Status updated for {phone_number} (message sent)")
                    else:
                        print(f"Failed to update status for {phone_number}")
                else:
                    print(f"Failed to send message to {name} at {phone_number}")

                    # Update the status in the API after failed sending
                    if update_status(phone_number, 'message_failed'):
                        print(f"Status updated for {phone_number} (message failed)")
                    else:
                        print(f"Failed to update failure status for {phone_number}")

                # Add a safe wait time between messages
                wait_time = random.randint(int(data.get('delay_min')), int(data.get('delay_max')))  # Random delay
                print(f"Waiting for {wait_time} seconds before sending the next message...")
                time.sleep(wait_time)  # Wait before sending the next message

        else:
            print("Failed to retrieve phone numbers")

    except Exception as e:
        print(f"Error occurred: {e}")

    # Wait for 60 seconds before checking again
    time.sleep(60)
