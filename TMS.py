from customtkinter import *
from PIL import Image
import tkinter as tk
from tkinter import ttk
from tkinter import messagebox
import database
import customtkinter as ctk
from fpdf import FPDF
import warnings
import arabic_reshaper
from bidi.algorithm import get_display
import os
from tkinter import simpledialog, messagebox
# Suppress specific warnings


# Create the main window
window = CTk()
screen_width = window.winfo_screenwidth()
screen_height = window.winfo_screenheight()
window.geometry(f"{screen_width}x{screen_height}")
window.resizable(1,1)
window.title('Talaba Management System')


# Load the image
image = Image.open('/home/bizk/tahfid/bt.png')
ctk_image = CTkImage(light_image=image, dark_image=image, size=(screen_width, 200))

# Create a label to display the image
imageLabel = CTkLabel(window, image=ctk_image, text='')
imageLabel.grid(row=0, column=0 , columnspan = 2)




# Functions
def treeview_data():
      # Clear the existing data in the treeview
    for row in tree.get_children():
        tree.delete(row)

    # Fetch data from the database
    rows = database.fetch_talaba()
    
    # Insert data into the treeview
    for row in rows:
        
        tree.insert('', 'end', values=row)
        




def search_all():
    treeview_data()
    searchbox.set('Search by')
    search_Entry.delete(0, 'end')

def update_talib():
    selected_item = tree.selection()
    if not selected_item:
        messagebox.showerror('Error', 'You must select a row to update')
    else:
        row = tree.item(selected_item)['values']
        database.update(row[0], nameEntry.get(), last_nameEntry.get(), mourajaaEntry.get(), presenceEntry.get(), conductEntry.get(), hifdEntry.get(), chikhEntry.get())
        treeview_data()
        clear()

def selection(event):
    selected_item = tree.selection()
    if selected_item:
        row = tree.item(selected_item)['values']
        clear()
        nameEntry.insert(0, row[1])
        last_nameEntry.insert(0, row[2])
        mourajaaEntry.insert(0, row[3])
        presenceEntry.insert(0, row[4])
        conductEntry.insert(0 ,row[5])
        hifdEntry.insert(0, row[6])
        chikhEntry.insert(0, row[7])


from tkinter import simpledialog, messagebox

def delete_all():
    # Prompt the user for a password
    password = simpledialog.askstring('Password Required', 'Please enter the password to delete all data:', show='*')
    
    # Check if the password matches
    if password == 'jiyuda':  # Replace 'your_password' with your actual password
        # If the password is correct, proceed with deletion
        database.delete_all_data()
        treeview_data()
        messagebox.showinfo('Success', 'All data has been deleted successfully.')
    else:
        # If the password is incorrect, show an error message
        messagebox.showerror('Error', 'Incorrect password. Deletion aborted.')


def clear(value=False):
    if value:
        tree.selection_remove(tree.focus())
    nameEntry.delete(0, 'end')
    last_nameEntry.delete(0, 'end')
    mourajaaEntry.delete(0, 'end')
    presenceEntry.delete(0, 'end')
    conductEntry.delete(0, 'end')
    hifdEntry.delete(0, 'end')
    chikhEntry.delete(0, 'end')

def delete_talib():
    selected_item = tree.selection()
    if not selected_item:
        messagebox.showerror('Error', 'Select a row to delete')
    else:
        row = tree.item(selected_item)['values']
        database.delete_row(row[0])
        treeview_data()
        clear()
        
def add_talib():
    # Check if any required fields are empty
    if nameEntry.get() == '' or mourajaaEntry.get() == '' or last_nameEntry.get() == '' or presenceEntry.get() == '' or hifdEntry.get() == '':
        messagebox.showerror("Error", "All fields are required")
    else:
        # Insert data into the database, omitting the ID and user_id fields
        database.insert(
            nameEntry.get(),               # first_name
            last_nameEntry.get(),          # last_name
            mourajaaEntry.get(),               # mourajaa
            presenceEntry.get(),           # absence
            conductEntry.get(),              # conduct
            hifdEntry.get(),               # hifdh
            chikhEntry.get()                 # chikh_name
        )

        # Refresh the treeview to show the updated data
        treeview_data()

        # Automatically scroll to the bottom (the last inserted row)
        tree.yview_moveto(1)  # Scroll to the bottom of the Treeview
        last_row = tree.get_children()[-1]  # Get the last row ID
        tree.see(last_row)  # Ensure the last row is visible

        # Clear input fields after adding the record
        clear()

def search_talib():
    if search_Entry.get() == '':
        messagebox.showerror('Error', 'Enter a value to search')
    elif searchbox.get() == 'Search by':
        messagebox.showerror('Error', 'Select a search category')
    else:
        searched_data = database.search(searchbox.get(), search_Entry.get())
        tree.delete(*tree.get_children()) 
        for talib in searched_data:                 
            tree.insert('', 'end', values=talib)

def search_taqrir():
    # Perform searches for each condition
    taqarir_presence = database.search('Absence', '3') + database.search('Absence' , '4') + database.search('Absence' , '5') + database.search('Absence' , '6')
    taqarir_conduct = database.search('conduct', '3') + database.search('conduct', '6')
    taqarir_hifdh = database.search('hifdh', '3') + database.search('hifdh', '6')
    taqarir_mourajaa = database.search('mourajaa', '3') + database.search('mourajaa', '6')
      
    # Combine the results
    searched_taqarir = taqarir_presence + taqarir_conduct + taqarir_hifdh + taqarir_mourajaa

    try:
        # Remove duplicates (if all results are dictionaries)
        searched_taqarir = [dict(t) for t in {tuple(d.items()) for d in searched_taqarir}]
    except AttributeError:
        # If search results are tuples, remove duplicates directly
        searched_taqarir = list(set(searched_taqarir))

    # Clear the treeview before inserting new results
    tree.delete(*tree.get_children())

    # Insert each Taqrir into the treeview
    for taqrir in searched_taqarir:
        tree.insert('', 'end', values=taqrir)


# Toggle between light and dark mode
dark_mode = True

def change_mode():
    global dark_mode

    if dark_mode:
        # Dark mode configuration
        window.configure(fg_color='#1A2026')
        leftFrame.configure(fg_color='#1A2026')
        rightFrame.configure(fg_color='#1A2026')
        buttonFrame.configure(fg_color='#1A2026')
        mode_button.configure(text='Light mode')

        # Style for the headings of the Treeview
        style.configure("Treeview.Heading",
                        font=('Arial', 13, 'bold'),  # Font for the headers
                        foreground='white',          # Text color for headers
                        background='#2A8285',        # Background color for headers
                        padding=[3, 3, 3, 3])        # Add padding to the headers

        # Style for the rows in the Treeview
        style.configure("Treeview",
                        font=('Arial', 13),          # Font for the treeview content
                        rowheight=28,                # Row height
                        background='#1A2026',        # Background color for rows
                        foreground='white',          # Text color for rows
                        fieldbackground='#1A2026')   # Background color for empty space

        # Configure label text colors
        nameLabel.configure(text_color='white')
        last_nameLabel.configure(text_color='white')
        mourajaaLabel.configure(text_color='white')
        presenceLabel.configure(text_color='white')
        conductLabel.configure(text_color='white')
        hifdLabel.configure(text_color='white')
        chikhLabel.configure(text_color='white')

        # Set a theme globally for all customtkinter widgets
        ctk.set_appearance_mode("dark")  # options are "dark", "light", or "system"
        ctk.set_default_color_theme("blue")  # you can choose themes like "blue", "green", etc.

    else:
        # Light mode configuration
        window.configure(fg_color='#B3D7D8')
        leftFrame.configure(fg_color='#B3D7D8')
        rightFrame.configure(fg_color='#B3D7D8')
        buttonFrame.configure(fg_color='#B3D7D8')
        mode_button.configure(text='Dark mode')

        # Style for the headings of the Treeview
        style.configure("Treeview.Heading",
                        font=('Arial', 13, 'bold'),  # Font for the headers
                        foreground='black',          # Text color for headers
                        background='#2A8285',        # Background color for headers
                        padding=[3, 3, 3, 3])        # Add padding to the headers

        # Style for the rows in the Treeview
        style.configure("Treeview",
                        font=('Arial', 13),          # Font for the treeview content
                        rowheight=28,                # Row height
                        background='#B3D7D8',        # Background color for rows
                        foreground='black',          # Text color for rows
                        fieldbackground='#B3D7D8')   # Background color for empty space

        # Configure label text colors
        nameLabel.configure(text_color='black')
        last_nameLabel.configure(text_color='black')
        mourajaaLabel.configure(text_color='black')
        presenceLabel.configure(text_color='black')
        conductLabel.configure(text_color='black')
        hifdLabel.configure(text_color='black')
        chikhLabel.configure(text_color='black')

       

        # Set a theme globally for all customtkinter widgets
        ctk.set_appearance_mode("light")  # options are "dark", "light", or "system"
        ctk.set_default_color_theme("green")  # you can choose themes like "blue", "green", etc.

    # Toggle the mode
    dark_mode = not dark_mode

# COPY PASTE EVENTS WAKARENAI 知りません  ＼( ･_･)

def copy_to_clipboard(event):
    event.widget.clipboard_clear()
    event.widget.clipboard_append(event.widget.selection_get())

def paste_from_clipboard(event):
    try:
        event.widget.insert(tk.INSERT, event.widget.clipboard_get())
    except tk.TclError:                                                
        pass


def show_context_menu(event): 
    menu = tk.Menu(window, tearoff=0)
    menu.add_command(label="Copy", command=lambda: copy_to_clipboard(event))
    menu.add_command(label="Paste", command=lambda: paste_from_clipboard(event))
    menu.tk_popup(event.x_root, event.y_root)
 


# Create left frame
leftFrame = CTkFrame(window)
leftFrame.grid(row=1, column=0, pady=5, padx=5)


# Create labels and entries 
nameLabel = CTkLabel(leftFrame, text='Name', font=('arial', 18, 'bold'))
nameLabel.grid(row=0, column=0, padx=20, pady=13, sticky='w')

nameEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
nameEntry.grid(row=0, column=1)

last_nameLabel = CTkLabel(leftFrame, text='Family Name', font=('arial', 18, 'bold'))
last_nameLabel.grid(row=1, column=0, padx=20, pady=13, sticky='w')

last_nameEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
last_nameEntry.grid(row=1, column=1)

mourajaaLabel = CTkLabel(leftFrame, text='Mourajaa', font=('arial', 18, 'bold'))
mourajaaLabel.grid(row=2, column=0, padx=20, pady=13, sticky='w')

mourajaaEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
mourajaaEntry.grid(row=2, column=1)

presenceLabel = CTkLabel(leftFrame, text='Absence', font=('arial', 18, 'bold'))
presenceLabel.grid(row=3, column=0, padx=20, pady=13, sticky='w')

presenceEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
presenceEntry.grid(row=3, column=1)

conductLabel = CTkLabel(leftFrame, text='Conduct', font=('arial', 18, 'bold'))
conductLabel.grid(row=4, column=0, padx=20, pady=13, sticky='w')

conductEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
conductEntry.grid(row=4, column=1)


hifdLabel = CTkLabel(leftFrame, text='Hifdh', font=('arial', 18, 'bold'))
hifdLabel.grid(row=5, column=0, padx=20, pady=13, sticky='w')

hifdEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
hifdEntry.grid(row=5, column=1)

chikhLabel = CTkLabel(leftFrame, text='Chikh', font=('arial', 18, 'bold'))
chikhLabel.grid(row=6, column=0, padx=20, pady=13, sticky='w')

chikhEntry = CTkEntry(leftFrame, font=('arial', 15, 'bold'), width=180)
chikhEntry.grid(row=6, column=1)

# Create the right frame
rightFrame = CTkFrame(window, width=1200, height=700)  
rightFrame.grid(row=1, column=1, padx=5, pady=5, columnspan=7, sticky='nsew')



# buttons

search_by = ['chikh_name', 'hifdh', 'age', 'conduct', 'Absence']
searchbox = CTkComboBox(rightFrame, values=search_by, 
                        font=('arial', 15, 'bold'), state='readonly' , width = 160)
searchbox.set('Search by')
searchbox.grid(row=0, column=0, padx=4)

search_Entry = CTkEntry(rightFrame, placeholder_text='Search...' ,width = 160 ,font=('arial', 15, 'bold'))
search_Entry.grid(row=0, column=1, padx=4)

searchbutton = CTkButton(rightFrame, text='Search',font=('arial', 15, 'bold'), corner_radius = 15, command=search_talib)
searchbutton.grid(row=0, column=2, padx=4)


search_taqrirbutton = CTkButton(rightFrame, text='Search Taqrir',font=('arial', 15, 'bold'), corner_radius = 15, command=search_taqrir)
search_taqrirbutton.grid(row=0, column=3,  padx=4 , pady=5)



# Creat tree

tree  = ttk.Treeview(rightFrame   , height = 13 )
tree.grid(row = 1 , column = 0 , columnspan = 5 )

tree['columns'] = ('ID' , 'name' , 'last_name'  , 'mourajaa'  , 
               'Absence' , 'conduct'  , 'hifdh','Chikh' )

tree.heading('ID' , text = 'ID' )
tree.heading('name' , text = 'Name' )
tree.heading('last_name' ,text = 'Family Name')
tree.heading('mourajaa'  , text  = 'Mourajaa')
tree.heading('Absence' , text = 'Absence')
tree.heading('conduct' , text = 'Conduct')
tree.heading('hifdh' , text = 'Hifdh')
tree.heading('Chikh' , text = 'Chikh')

tree.config(show = 'headings')

tree.column('ID' , anchor = CENTER , width = 50)
tree.column('name' , anchor = CENTER  , width = 190)
tree.column('last_name' , anchor = CENTER  , width = 190)
tree.column('mourajaa' , anchor = CENTER  , width = 110)
tree.column('Absence' , anchor = CENTER  , width = 90)
tree.column('conduct' , anchor = CENTER  , width = 100)
tree.column('hifdh' , anchor = CENTER , width = 100)
tree.column('Chikh' , anchor = CENTER  , width = 160)



# Function to reshape and reverse the text for right-to-left display
def prepare_rtl_text(text):
    reshaped_text = arabic_reshaper.reshape(text)  # Reshape the Arabic text
    bidi_text = get_display(reshaped_text)         # Apply BiDi algorithm for RTL text
    return bidi_text

def generate_Taqrir(tree):
    
    # Get selected data from the treeview
    selected_item = tree.selection()
    row = tree.item(selected_item)['values']
    database.show_taqrir(row[0] , row[3] , row[4] , row[5] ,row[6])
    if not selected_item:
        messagebox.showerror('Error', 'Select a row')
    else:
        row = tree.item(selected_item)['values']  # Get the values of the selected row

        # Clear the input fields
        clear()
        
        # Store the name and last name for report generation
        name_taqrir = row[1]  # Assuming row[1] is the name
        last_name_taqrir = row[2]  # Assuming row[2] is the last name

        # Generate the Arabic introduction using the selected values
        introduction = (
            f"  نشعركم أن التلميذ {name_taqrir} {last_name_taqrir} قد تمت الملاحظة عليه من قبل إدارة المدرسة. "
        )

        return introduction



# Save report to PDF with Arabic support
def save_recipe_to_pdf(recipe_text, directory="/home/bizk/taqarir"):
    # Construct the filename dynamically using the name parameters
    selected_item = tree.selection()

    row = tree.item(selected_item)['values']  # Get the values of the selected row
      
    # Clear the input fields
    clear()
            
    # Store the name and last name for report generation
    name_taqrir = row[1]  # Assuming row[1] is the name
    last_name_taqrir = row[2]  # Assuming row[2] is the last name
    id_taqrir = row[0]
    filename = f"Talib_{id_taqrir}_taqrir.pdf"
    
    # Create the full file path by joining the directory and filename
    full_path = os.path.join(directory, filename)
    
    # Ensure the directory exists
    if not os.path.exists(directory):
        os.makedirs(directory)

    # Create a PDF instance
    pdf = FPDF()
    pdf.add_page()

    # Use an Arabic compatible font (Ensure 'Amiri-Regular.ttf' is available in the specified directory)
    pdf.add_font("Amiri", '', '/home/bizk/tahfid/Amiri-Regular.ttf', uni=True)
    pdf.set_font("Amiri", size=15)

    # Add a title in Arabic
    title = prepare_rtl_text("تقرير رقم 1")
    pdf.cell(200, 10, txt=title, ln=True, align='C')
    pdf.ln(10)  # Line break
    
    # Prepare the RTL text for the recipe
    pdf.set_font("Amiri", size=12)
    recipe_text_rtl = prepare_rtl_text(recipe_text)

    # Write the RTL text to the PDF
    pdf.multi_cell(0, 10, txt=recipe_text_rtl, align='R')
    
    # Save the PDF to the specified directory
    pdf.output(full_path)
    
    messagebox.showinfo('Success' , f'The Taqrir is seved to {full_path}')
    

# Generate the report and save it as PDF
# Create a button that generates and saves the report when clicked
print_taqrirbutton = CTkButton(
    rightFrame, 
    text='Print Taqrir', 
    font=('arial', 15, 'bold'), 
    corner_radius=15,
    command=lambda: save_recipe_to_pdf(generate_Taqrir(tree))
)
print_taqrirbutton.grid(row=0, column=4, padx=4, pady=5)



style = ttk.Style()


# You may need to set the row colors alternatively (optional)
style.map('Treeview', background=[('selected', '#347083')], foreground=[('selected', 'white')])


scrollbar = ttk.Scrollbar(rightFrame  , orient = VERTICAL , command = tree.yview)
scrollbar.grid(row = 1 , column = 5   , sticky = 'ns')

tree.config(yscrollcommand = scrollbar.set)


#creat the button frame
buttonFrame = CTkFrame(window )
buttonFrame.grid(row = 2 , column = 0 , columnspan = 2 , padx= 100)

# Ensure the Treeview and Scrollbar are expandable

rightFrame.grid_rowconfigure(1, weight=1)


# Create a Style Object for Scrollbar
style = ttk.Style()
style.configure("Vertical.TScrollbar",
                gripcount=0,                      # Number of grip icons
                background="#FFFFFF",             # Background color of the scrollbar
                troughcolor="#2A8285",            # Color of the trough
                arrowcolor="#2A8285",             # Color of the arrows
                borderwidth=0,                    # Border width around the scrollbar
                relief="flat")                    # Relief style of the scrollbar




Newbutton = CTkButton(buttonFrame , text = 'New Talib',width = 140 
 , font=('arial', 15, 'bold') , corner_radius = 15 , command = lambda: clear(True) )
Newbutton.grid(row=0 , column =0, padx = 10 , pady = 20)

addbutton = CTkButton(buttonFrame , text = 'Add Talib',width = 140
 , font=('arial', 15, 'bold'), corner_radius = 15 ,command = add_talib)
addbutton.grid(row=0 , column =1, padx = 10 , pady = 5)

updatebutton = CTkButton(buttonFrame , text = 'Update Talib',width = 140
 , font=('arial', 15, 'bold'), corner_radius = 15 ,command = update_talib )
updatebutton.grid(row=0 , column =2, padx = 10 , pady = 5)

delete_talibbutton = CTkButton(buttonFrame , text = 'Delete Talib',width = 140
 , font=('arial', 15, 'bold'), corner_radius = 15 , command = delete_talib)
delete_talibbutton.grid(row=0 , column =3, padx = 10 , pady = 5)

delete_allbutton = CTkButton(buttonFrame , text = 'Delete All',width = 140
 , font=('arial', 15, 'bold'), corner_radius = 15 , command = delete_all)
delete_allbutton.grid(row=0 , column =4, padx = 10 , pady = 5)

showbutton = CTkButton(buttonFrame, text='Show All',width = 140, font=('arial', 15, 'bold'), corner_radius = 15 ,command=search_all)
showbutton.grid(row=0, column=5 , padx=4, pady=5)

mode_button = CTkButton(buttonFrame ,width = 140, font=('arial', 15, 'bold'), corner_radius = 15 , command = change_mode)
mode_button.grid(row=0 , column =6, padx = 10 , pady = 5)




# Bind right-click to show the context menu
nameEntry.bind('<Button-3>', show_context_menu)
last_nameEntry.bind('<Button-3>', show_context_menu)
mourajaaEntry.bind('<Button-3>', show_context_menu)
presenceEntry.bind('<Button-3>', show_context_menu)
hifdEntry.bind('<Button-3>', show_context_menu)


# Finalize UI
treeview_data()
window.bind('<ButtonRelease-1>', selection)

# Ensure that the grid allows expansion
window.grid_columnconfigure(1, weight=1)
window.grid_rowconfigure(1, weight=1)

change_mode()

window.mainloop()


# 


