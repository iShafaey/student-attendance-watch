# Student Attendance Watch

![alt text](https://github.com/iShafaey/student-attendance-watch/blob/main/public/student-attendance-watch.png?raw=true)

## Overview

**Name:** Student Attendance Watch  
**Objective:** Develop a system to allow teachers or educational institutions to manage student data and verify attendance effectively. Parents can check if their child has attended or not.

## Key Features

### Add Students
User-friendly interface for adding student data, including:
- **Student Name**
- **Parent’s Name**
- **Phone Number**
- **Joining Date**
- **Student Code**
- **Fees**
- **Grade Level**

### Attendance Verification
- **Barcode Scanning:** Enables students to check in quickly and efficiently by scanning a barcode.
- **WhatsApp Notification:** Automatically sends a WhatsApp message to the parent when a student checks in.
- **Attendance Status Update:** The system reads the barcode and updates the attendance status.

### Data Export
- **Excel Export:** Capability to export student data to an Excel file, including the student code and other details.

### Notification System
- **Python Script Integration:** Uses a Python script to query parent contact numbers via an API.
- **Customizable Notifications:** Sends a text message to parents upon student check-in, with customizable templates for message text.
- **Control Settings:** Easily manage settings like message text and delay intervals between notifications.

### Attendance Status Update
- **Automated Status Check:** A Python script updates the attendance status as "Sent" or "Failed" based on message delivery.

## Benefits
- Enhances student data management.
- Speeds up attendance check-in.
- Facilitates communication with parents.
- Enables efficient tracking of attendance and message statuses.

# Update Log

**Date:** 2024-11-12
## Recent Updates

1. **Enhanced Message Console**
    - Improved the Python-based message provider console.
    - Added colored indicators for different message states.

2. **Daily Automatic Absence Check**
    - Added a feature to automatically verify student attendance daily at 7:00 PM.

3. **Fee Reminder Notifications for Guardians**
    - Added automatic reminders for unpaid fees, notifying the guardian on the 26th, 27th, and 28th of each month.

4. **Scheduled Tasks for Automatic Verification**
    - Utilized Laravel's scheduling services to handle the automated checks in points #2 and #3.

5. **Student Class And Subjects**
    - The student can now be linked to the class..

6. **Parent Notification for Student Results**
    - Implemented notifications to inform the guardian of the student’s academic results.

7. **Tuition Fee Payment Confirmation Notification**
    - Added feature to notify the guardian when a student completes a tuition payment.

8. **Enhanced WhatsApp Message Sending in Python**
    - Improved the Python code for the message provider, ensuring smooth WhatsApp message sending without issues.

9. **Student Data Export with Barcode**
    - Added the ability to export student data along with student barcodes in `.PNG` format.

## Requirements
- **PHP:** Version >= 8.2
- **Python:** Version >= 3.6
- **Chrome Headless:** Installed automatically by the program.
- **Barcode Scanner:** Any Barcode Scanner.

## Installation Guide

### Laravel Setup
1. Run the command:
   ```bash
   composer install
   ```
2. Start the server with:
   ```bash
   php artisan serve
   ```

### Python Setup
1. Navigate to the following path:
   ```
   public/services/
   ```
2. Open this path in Terminal as Administrator.
3. Install dependencies with:
   ```bash
   pip install -r requirements.txt
   ```
4. Run WhatsApp messaging provider:
   ```bash
   /whatsapp-sender/run-service.bat
   ```
5. Run the script to convert students' barcodes to `.PNG` format (Must export the student file.):
   ```bash
   /barcode-generator/run-service.bat
   ```

### WhatsApp Web Login

Log in via WhatsApp Web to complete setup, then test the system.

## Api Respoinse
- get-numbers

```json
   {
    "delay_min": "60",
    "delay_max": "80",
    "contacts": [
            {
                "phone_number": "+20100000000",
                "name": "محمد",
                "message": "نود إبلاغكم بأن الطالب محمد قد حضر اليوم في تمام الساعة 12:24 AM.",
                "type": "attendance_in" | "attendance_out" | "absence" | "expenses" | "expenses_reminder" | "exam"
            }
        ]
    }
   ```

- update-status

```json
   {
    "status": "success"
   }
   ```

## Api Update Status
- update-status

```json
   {
    "phone_number": "+20100000000",
    "status": "message_sent",
    "type": "attendance_in"
    }
   ```

## License

Student Attendance Watch is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
