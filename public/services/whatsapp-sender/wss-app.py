import os
import time
import random

import pyfiglet
import requests
from datetime import datetime
from selenium import webdriver
from selenium.common import NoSuchElementException
from selenium.webdriver.common.keys import Keys
from webdriver_manager.chrome import ChromeDriverManager
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.chrome.options import Options
from colorama import Fore, Style, init

# Initialize colorama
init(autoreset=True)


# Define print plus
def print_plus(type, message, message_color):
    type = (type or "SYSTEM")
    # total_length = 8
    # spaces = ' ' * (total_length - len(type))

    current_time = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
    print(f"{Style.DIM + Style.BRIGHT + Fore.WHITE}[{current_time}] "
          f"[{Style.BRIGHT + Fore.YELLOW}{type.ljust(8)}{Style.RESET_ALL}] "
          f"{message_color}{message}{Style.RESET_ALL}")


# Create browser options
options = Options()

# Define the user-data path based on the operating system
user_data_path = os.path.join(os.getenv("LOCALAPPDATA"), "Google", "Chrome", "User Data")
options.add_experimental_option("excludeSwitches", ["enable-logging"])
options.add_argument("--timeout=60")
options.add_argument(f"--user-data-dir={user_data_path}")
options.add_argument("--profile-directory=Default")
options.add_argument("--remote-debugging-port=9222")
options.add_argument("--window-size=1200,800")
options.add_argument("--window-position=0,0")

# Init driver and welcome
wait_time = 15;
nextCheck = 30
text = "Student Attendance Watch"
ascii_art = pyfiglet.figlet_format(text)
print(ascii_art)

print_plus(type="WELCOME", message=f"{text}...", message_color=Style.DIM + Fore.CYAN)
print_plus(type="CONFIG", message=f"Driver: selenium", message_color=Style.DIM + Fore.CYAN)
print_plus(type="CONFIG", message=f"Browser: Chrome", message_color=Style.DIM + Fore.CYAN)
print_plus(type="CONFIG", message=f"Target service: Whatsapp", message_color=Style.DIM + Fore.CYAN)
print_plus(type="CONFIG", message=f"Api Check Time: {nextCheck} sec", message_color=Style.DIM + Fore.CYAN)
print_plus(type="CONFIG", message=f"General Wait Time: {wait_time} sec", message_color=Style.DIM + Fore.CYAN)

time.sleep(1)

print_plus(type="CONFIG", message=f"Driver service registration...", message_color=Style.DIM + Fore.BLUE)

# Set up Chrome browser using webdriver_manager
service = Service(ChromeDriverManager().install())
driver = webdriver.Chrome(service=service, options=options)

print_plus(type="CONFIG", message=f"Driver service registered...", message_color=Style.DIM + Fore.GREEN)

# Define API URLs
api_url = 'http://127.0.0.1:8000/api/v1.0/attendances/students/get-numbers'
update_api_url = 'http://127.0.0.1:8000/api/v1.0/attendances/students/update-status'

blacklist_file = "blacklist.txt"
whitelist_file = "whitelist.txt"
base_dir = os.path.abspath(os.path.dirname(__file__))
blacklist_path = os.path.join(base_dir, blacklist_file)
whitelist_path = os.path.join(base_dir, whitelist_file)


# Read blacklist file
def load_blacklist(file_path):
    if not os.path.exists(file_path):
        return set()
    with open(file_path, "r", encoding="utf-8") as f:
        return set(line.strip() for line in f if line.strip())


# Check if the phone number is in the blacklist file
def check_blacklist(phone_number):
    # Ensure the blacklist file exists
    if not os.path.exists(blacklist_file):
        with open(blacklist_file, "w") as f:
            pass  # Create the file if it doesn't exist

    # Read the blacklist
    with open(blacklist_file, "r") as f:
        blacklist = f.read().splitlines()

    # Check if the number is in the blacklist
    if phone_number in blacklist:
        print_plus(type="SYSTEM", message=f"Number {phone_number} is already in the blacklist.",
                   message_color=Fore.RED)
        return True

    # Add the number to the blacklist
    with open(blacklist_file, "a") as f:
        f.write(phone_number + "\n")
    print_plus(type="SYSTEM", message=f"Number {phone_number} has been added to the blacklist.",
               message_color=Fore.RED)
    return False


def check_number_at_blacklist(number):
    with open(blacklist_file, 'r') as file:
        blacklist = file.read().splitlines()

    if number in blacklist:
        return True
    else:
        return False


def check_whitelist(phone_number):
    # Ensure the whitelist file exists
    if not os.path.exists(whitelist_file):
        with open(whitelist_file, "w") as f:
            pass  # Create the file if it doesn't exist

    # Read the whitelist
    with open(whitelist_file, "r") as f:
        whitelist = f.read().splitlines()

    # Check if the number is in the whitelist
    if phone_number in whitelist:
        print_plus(type="SYSTEM", message=f"Number {phone_number} is already in the whitelist.",
                   message_color=Fore.GREEN)
        return True

    # Add the number to the whitelist
    with open(whitelist_file, "a") as f:
        f.write(phone_number + "\n")
    print_plus(type="SYSTEM", message=f"Number {phone_number} has been added to the whitelist.",
               message_color=Fore.GREEN)
    return False


def check_number_at_whitelist(number):
    with open(whitelist_file, 'r') as file:
        whitelist = file.read().splitlines()

    if number in whitelist:
        return True
    else:
        return False


# Filter contacts
def filter_contacts(contacts, blacklist):
    if not isinstance(contacts, list):
        raise ValueError("Expected 'contacts' to be a list of dictionaries.")
    return [contact for contact in contacts if contact.get('phone_number') not in blacklist]


# Check whatsapp number
def check_whatsapp_number(driver, phone_number, message):
    if check_number_at_whitelist(phone_number): return True

    print_plus(type="SYSTEM", message=f"Checking Number {phone_number} is registered on WhatsApp or not...",
               message_color=Fore.CYAN)

    try:
        # Open the WhatsApp Web URL with the phone number
        whatsapp_url = f"https://web.whatsapp.com/send?phone={phone_number}&text={message}"
        driver.get(whatsapp_url)

        time.sleep(wait_time)  # Wait for WhatsApp Web to process the request

        try:
            # Check for error message indicating the number is not on WhatsApp
            error_message = driver.find_element(By.XPATH,
                                                '//div[contains(text(), "Phone number shared via url is invalid.")]')
            print_plus(type="WHATSAPP", message=f"Number {phone_number} is not registered on WhatsApp.",
                       message_color=Fore.RED)
            # Add the number to the blacklist
            check_blacklist(phone_number)
            return False
        except NoSuchElementException:
            # If the error message is not found, the number is on WhatsApp
            print_plus(type="WHATSAPP", message=f"Number {phone_number} is registered on WhatsApp.",
                       message_color=Fore.GREEN)
            check_whitelist(phone_number)
            return True

    except Exception as e:
        print_plus(type="SYSTEM", message=f"An error occurred: {type(e).__name__}", message_color=Fore.RED)
        return False


# Function to update the status after sending the message successfully or failing
def update_status(phone_number, status, msg_type):
    try:
        if check_number_at_blacklist(phone_number):
            response = requests.post(update_api_url, json={
                'phone_number': phone_number,
                'status': "number_blacklisted",
                'type': msg_type
            })
        else:
            response = requests.post(update_api_url, json={
                'phone_number': phone_number,
                'status': status,
                'type': msg_type
            })

        response.raise_for_status()  # Raises HTTPError for bad responses (4xx and 5xx)
        return True  # If the request is successful
    except requests.exceptions.RequestException as e:
        # Print the error details and return False
        error_message = f"An error occurred: {type(e).__name__}, Status Code: {getattr(e.response, 'status_code', 'N/A')}"
        print_plus(type="SYSTEM", message=error_message, message_color=Fore.RED)
        return False


# Function to send a message via WhatsApp Web
def send_whatsapp_message(phone_number, message):
    print_plus(type="SYSTEM", message=f"Started sending message to {phone_number}", message_color=Fore.CYAN)
    try:
        # Open the link with the phone number
        whatsapp_url = f"https://web.whatsapp.com/send?phone={phone_number}&text={message}"
        driver.get(whatsapp_url)
        time.sleep(5)
        # Hide and resize the browser window
        driver.set_window_position(10000, 10000)
        driver.set_window_size(400, 400)

        if check_whatsapp_number(driver, phone_number, message) == False:
            return False

        try:
            # Try the primary method
            # message_box = driver.find_element(By.XPATH,'//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[1]/div/div[1]/p')
            send_button = driver.find_element(By.XPATH,
                                              '//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[2]/button')
            time.sleep(1)
            send_button.click()
        except Exception as primary_error:
            # If the primary method fails, try the alternative method
            try:
                message_box = driver.find_element(By.XPATH,
                                                  '//*[@id="main"]/footer/div[1]/div/span/div/div[2]/div[1]/div/div[1]/p')
                time.sleep(1)
                message_box.send_keys(Keys.ENTER)
            except Exception as secondary_error:
                print_plus(type="SYSTEM", message=f"Alternative method also failed: {type(secondary_error).__name__}",
                           message_color=Fore.RED)
                return False

        return True

    except Exception as e:
        print_plus(type="SYSTEM", message=f"Failed to send message: {type(e).__name__}", message_color=Fore.RED)
        return False


# Infinite loop to query every 60 seconds
while True:
    try:
        # Query the numbers from the API
        response = requests.get(api_url)

        if response.status_code == 200:
            data = response.json()
            contacts_res = data.get('contacts', [])
            blacklist = load_blacklist(blacklist_path)
            filtered_contacts = filter_contacts(contacts_res, blacklist)
            contacts = data['contacts'] = filtered_contacts
            if not contacts:
                print_plus(type="SYSTEM", message=f"No new messages to fetch.",
                           message_color=Fore.MAGENTA + Style.BRIGHT)

            total_contacts = len(contacts)
            sent_count = 0

            if total_contacts:
                print_plus(type="SYSTEM", message=f"Found {total_contacts} new messages.",
                           message_color=Fore.GREEN + Style.BRIGHT)

            # Iterate over the numbers and send messages
            for contact in contacts:
                phone_number = contact.get('phone_number')
                name = contact.get('name')
                message = contact.get('message')
                msg_type = contact.get('type')  # Update to use 'msg_type' instead of 'type'

                if check_number_at_blacklist(phone_number):
                    print_plus(type="SYSTEM", message=f"Number {phone_number} was skipped because it was blacklisted.",
                               message_color=Style.DIM + Fore.RED)
                    continue

                if send_whatsapp_message(phone_number, message):
                    sent_count += 1
                    remaining_count = total_contacts - sent_count
                    print_plus(type="WHATSAPP",
                               message=f"Message sent to {phone_number} - Sent: {sent_count}, Remaining: {remaining_count}",
                               message_color=Fore.GREEN)

                    # Update the status in the API after successful sending
                    if update_status(phone_number, 'message_sent', msg_type):
                        print_plus(type="SYSTEM", message=f"Status updated for {phone_number} (message sent)",
                                   message_color=Fore.MAGENTA + Style.BRIGHT)
                    else:
                        print_plus(type="SYSTEM", message=f"Failed to update status for {phone_number}",
                                   message_color=Fore.RED)
                else:
                    print_plus(type="WHATSAPP", message=f"Failed to send message to {phone_number}",
                               message_color=Fore.RED)

                    # Update the status in the API after failed sending
                    if update_status(phone_number, 'message_failed', msg_type):
                        print_plus(type="SYSTEM", message=f"Status updated for {phone_number} (message failed)",
                                   message_color=Fore.MAGENTA + Style.BRIGHT)
                    else:
                        print_plus(type="SYSTEM", message=f"Failed to update failure status for {phone_number}",
                                   message_color=Fore.RED)

                # Add a safe wait time between messages
                wait_time = random.randint(int(data.get('delay_min')), int(data.get('delay_max')))  # Random delay
                print_plus(type="SYSTEM",
                           message=f"Waiting for {wait_time} seconds before sending the next message...",
                           message_color=Fore.YELLOW)
                time.sleep(wait_time)  # Wait before sending the next message
        else:
            print_plus(type="SYSTEM", message=f"Failed to retrieve phone numbers", message_color=Fore.RED)

    except Exception as e:
        print_plus(type="SYSTEM", message=f"Error occurred: {type(e).__name__}", message_color=Fore.RED)

    # Wait before checking again
    print_plus(type="SYSTEM", message=f"Waiting for {nextCheck} seconds before fetching new messages...",
               message_color=Fore.YELLOW)
    time.sleep(nextCheck)
