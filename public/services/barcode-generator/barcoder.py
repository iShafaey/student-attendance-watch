import pandas as pd
import os
from barcode import Code128
from barcode.writer import ImageWriter

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

            # Convert the student code to a string for barcode generation
            student_code_str = str(int(student_code))

            # Generate the barcode using the student code
            barcode = Code128(student_code_str, writer=ImageWriter())
            barcode_filename = os.path.join(output_folder, f"{student_name}")  # Use student name as file name

            # Save the barcode image
            barcode.save(barcode_filename)
            print(f"Barcode generated for student: {student_name} (code: {student_code_str}) saved in {barcode_filename}")

        except Exception as e:
            # Handle any error that occurs during barcode generation and saving
            print(f"Error generating barcode for student {student_name} (code: {student_code}): {e}")

if __name__ == "__main__":
    generate_barcodes()
