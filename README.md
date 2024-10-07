# Student Attendance Watch

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

## Requirements
- **PHP:** Version >= 8.2
- **Python:** Version >= 3.6
- **Chrome Headless:** Installed automatically by the program.

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
   public/services/whatsapp-sender
   ```
2. Open this path in Terminal as Administrator.
3. Install dependencies with:
   ```bash
   pip install -r requirements.txt
   ```
4. Run the script using:
   ```bash
   run-service.bat
   ```

### WhatsApp Web Login

Log in via WhatsApp Web to complete setup, then test the system.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
