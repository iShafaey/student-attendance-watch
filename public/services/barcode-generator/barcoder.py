import pandas as pd
import os
from barcode import Code128
from barcode.writer import ImageWriter
import qrcode

def generate_barcodes():
    # Define the base directory of the project
    base_dir = os.path.abspath(os.path.dirname(__file__))
    print(f"Base directory: {base_dir}")

    # Define input and output folder paths
    input_folder = os.path.abspath(os.path.join(base_dir, "..", "..", "downloads", "exports"))
    output_folder = os.path.join(input_folder, "barcodes")

    # Path to the CSV file containing student data
    csv_path = os.path.join(input_folder, 'students_list_barcodes.csv')

    # Check if the CSV file exists
    if not os.path.exists(csv_path):
        print(f"File '{csv_path}' Not Found")
        return

    # Create the barcodes output folder if it does not exist
    os.makedirs(output_folder, exist_ok=True)

    # Read the CSV file
    df = pd.read_csv(csv_path)

    # Check for required columns (student_code and student_name)
    if 'student_code' not in df.columns or 'student_name' not in df.columns:
        print("Error: CSV file must contain columns 'student_code' and 'student_name'.")
        return

    # Iterate over each row in the CSV to generate a barcode for each student
    for index, row in df.iterrows():
        try:
            student_code = row['student_code']
            student_name = row['student_name']

            # Validate that the student code contains only digits
            if not str(student_code).isdigit():
                print(f"Invalid student code: {student_code} (must be numeric). Skipping.")
                continue

            # Convert the student code to a string for QR generation
            student_code_str = str(int(student_code))

            # Generate the QR code using the student code
            qr = qrcode.QRCode(
                version=40,  # Size of the QR code
                error_correction=qrcode.constants.ERROR_CORRECT_L,  # Error correction level
                box_size=10,  # Size of each box in the QR code
                border=4,  # Width of the border
            )
            qr.add_data(student_code_str)  # Add the student code as data
            qr.make(fit=True)

            # Generate the QR code image
            img = qr.make_image(fill_color="black", back_color="white")

            # Save the QR code image
            qr_filename = os.path.join(output_folder, f"{student_name}.png")  # Use student name as file name
            img.save(qr_filename)

            print(f"QR code generated for student: {student_name} (code: {student_code_str}) saved in {qr_filename}")

        except Exception as e:
            # Handle any error that occurs during barcode generation and saving
            print(f"Error generating barcode for student {student_name} (code: {student_code}): {e}")

if __name__ == "__main__":
    generate_barcodes()
