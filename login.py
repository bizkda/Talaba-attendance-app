
from customtkinter import *
from PIL import Image
import tkinter as tk
from tkinter import messagebox

# Initialize the CustomTkinter root window
root = CTk()
root.geometry('930x520')
root.resizable(1,1)
root.title('Login Page')

# load the image
image = Image.open('/home/bizk/tahfid/cover.jpeg')
ctk_image = CTkImage(light_image=image, dark_image=image, size=(925, 520))

# Create a label to display the image
imageLabel = CTkLabel(root, image=ctk_image, text='')
imageLabel.place(x=0, y=0)

# Create the heading label (without 'bg_color')
heading_label = CTkLabel(root,  fg_color = '#EEE8DA',
                                text='TALABA MANAGEMENT SYSTEME', 
	                            font=('DejaVu Math TeX Gyre', 25, 'bold' ) ,
	                            text_color = '#163547')
heading_label.place(x=230, y=50)

# Getting the login informations
usernameEntry = CTkEntry(root, placeholder_text='Enter Your Username', width=180)
usernameEntry.place(x=20, y=200)

passwordEntry = CTkEntry(root, placeholder_text='Enter Your Password',
 width=180, show='*' )
passwordEntry.place(x=20, y=250)
# define the login methode
def login():
    username = usernameEntry.get()
    password = passwordEntry.get()
    
    if username == "" or password == "":
        messagebox.showerror("Error", "All fields are required")
    elif username == "bizk" and password == "jskamobe":
        # get to home
        root.destroy()
        import TMS
    else:
        messagebox.showerror("Error", "Wrong Username or Password")

loginButton = CTkButton(root, text='Login' , cursor = 'hand2' , command = login)
loginButton.place(x=32, y=300)


# Start the main loop
root.mainloop()
