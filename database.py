import pymysql
from tkinter import messagebox



# Global variables for connection and cursor
mycnx = None
mycursor = None

# Connect to database
def connect_database():
    global mycnx, mycursor
    try:
        mycnx = pymysql.connect(
            host='localhost',
            user='root',
            port=3307,  # Ensure this is correct; XAMPP uses 3306 by default
            password='',  # Replace with your actual password
            database='tahfid'  # Make sure the database name is correct
        )
        mycursor = mycnx.cursor()
        print("Connected to the database")  # Print success message
    except Exception as e:
        print(f"Connection Error: {str(e)}")  # Print error to console for debugging
        messagebox.showerror("Connection Error", f"Error: {str(e)}")
        return


# Insert new record into chikh_talaba3 table
def insert(first_name, last_name, date, absence, conduct, hifdh, chikh_name):
    try:
        sql = "INSERT INTO chikh_talaba3 (first_name, last_name, date, absence, conduct, hifdh, chikh_name) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        val = (first_name, last_name, date, absence, conduct, hifdh, chikh_name)
        mycursor.execute(sql, val)
        mycnx.commit()
        messagebox.showinfo("Success", "Record inserted successfully.")
    except Exception as e:
        messagebox.showerror("Insert Error", f"Error: {str(e)}")

# Fetch all records from chikh_talaba3
def fetch_talaba():
    try:
        mycursor.execute('SELECT * FROM chikh_talaba3')
        result = mycursor.fetchall()
        return result
    except Exception as e:
        messagebox.showerror("Fetch Error", f"Error: {str(e)}")
        return []

# Get the total number of rows in chikh_talaba3
def rows_number():
    mycursor.execute('SELECT COUNT(*) FROM chikh_talaba3')
    result1 = mycursor.fetchone()  # fetchone() is more appropriate here since it returns a single row
    return result1[0]  # result1 is a tuple, so access the first element

# Update an existing record in chikh_talaba3
def update(id, new_first_name, new_last_name, new_mourajaa, new_absence, new_conduct, new_hifdh, new_chikh_name):
    try:
        sql = 'UPDATE chikh_talaba3 SET first_name_fr = %s, last_name_fr = %s, mourajaa = %s, absence = %s, conduct = %s, hifdh = %s, chikh_name = %s WHERE id = %s'
        val = (new_first_name, new_last_name, new_mourajaa , new_absence, new_conduct, new_hifdh, new_chikh_name, id)
        mycursor.execute(sql, val)
        mycnx.commit()
        
    except Exception as e:
        messagebox.showerror("Update Error", f"Error: {str(e)}")

def show_taqrir(id, mourajaa, absence, conduct, hifdh):
    try:
        # Initialize an empty list to store all reports
        reports = []

        # Check for each milestone exactly
        if 3 <= mourajaa < 6:
            reports.append("التقرير الأول في المراجعة.")
        elif mourajaa >= 6:
            reports.append("التقرير الثاني في المراجعة.")
        
        if 3 <= hifdh < 6:
            reports.append("التقرير الأول في الحفظ.")
        elif hifdh >= 6:
            reports.append("التقرير الثاني في الحفظ.")
        
        if 3 <= absence < 6:
            reports.append("التقرير الأول في الغياب.")
        elif absence >= 6:
            reports.append("التقرير الثاني في الغياب.")
        
        if 3 <= conduct < 3:
            reports.append("التقرير الأول في السلوك.")
        elif conduct >= 6:
            reports.append("التقرير الثاني في السلوك.")

        # First, check if there is any existing data and erase it
        if reports:  # Only proceed if there are new reports
            # Set taqarir to an empty string if it already contains data
            reset_sql = 'UPDATE chikh_talaba3 SET taqarir = "" WHERE id = %s AND taqarir IS NOT NULL'
            mycursor.execute(reset_sql, (id,))
            mycnx.commit()

            # Now, loop through each report and append it to the taqarir field
            for report in reports:
                sql = '''
                UPDATE chikh_talaba3 SET taqarir = IFNULL(CONCAT(taqarir, '\n', %s), %s) WHERE id = %s
                '''
                mycursor.execute(sql, (report, report, id))
            
            mycnx.commit()  # Commit after inserting all reports

    except Exception as e:
        messagebox.showerror("Update Error", f"Error: {str(e)}")

  
        
        
            
        

         


# Delete a specific row by id in chikh_talaba3
def delete_row(id):
    try:
        mycursor.execute('DELETE FROM chikh_talaba3 WHERE id = %s', (id,))
        mycnx.commit()
        messagebox.showinfo("Success", "Record deleted successfully.")
    except Exception as e:
        messagebox.showerror("Delete Error", f"Error: {str(e)}")

# Search for a record by a specific column and value in chikh_talaba3
def search(option, value):
    try:
        mycursor.execute(f'SELECT * FROM chikh_talaba3 WHERE {option} = %s', (value,))
        result = mycursor.fetchall()
        return result
    except Exception as e:
        messagebox.showerror("Search Error", f"Error: {str(e)}")
        return []

# Delete all data in chikh_talaba3
def delete_all_data():
    try:
        mycursor.execute('TRUNCATE TABLE chikh_talaba3')
        mycnx.commit()
        messagebox.showinfo("Success", "All records deleted successfully.")
    except Exception as e:
        messagebox.showerror("Delete All Error", f"Error: {str(e)}")

# Call the function to initialize the database connection
connect_database()
