# 🚀 SRM Panel (File Manager)

A lightweight and powerful **web-based file manager panel** built for easy file handling, management, and server control.

---

## 👨‍💻 Developer
**Ottking Team**  
Maintained by **Anirban**

---

## 📌 Build Information
- **Version:** 11.106.0.11  
- **Release Type:** Stable  
- **Platform:** Web (PHP आधारित)

---

## 🔑 Default Login
Password:
```
admin123
```

⚠️ **Important:** First login এর পর অবশ্যই password change করে নাও।

---

## ✨ Features
- 📂 File Upload / Download  
- 📝 File Edit (online editor)  
- 🗑️ Delete / Rename files & folders  
- 📁 Folder Management  
- 🔒 Secure Login System  
- ⚡ Fast & Lightweight UI  
- 🌐 Web-based access (any device)

---

## 🛠️ Requirements
- PHP 7.0+  
- Apache / Nginx  
- unzip / git  
- Internet connection  

---

## ⚙️ Installation (Ubuntu - Command Line)

### 🔹 Step 1: System Update
```bash
sudo apt update && sudo apt upgrade -y
```

---

### 🔹 Step 2: প্রয়োজনীয় প্যাকেজ install
```bash
sudo apt install apache2 php unzip git -y
```

---

### 🔹 Step 3: Project Download (GitHub)
```bash
git clone https://github.com/anirbansumon099/srm-panel.git
```

---

### 🔹 Step 4: Move to Web Directory
```bash
sudo mv srm-panel /var/www/html/
cd /var/www/html/srm-panel
```

---

### 🔹 Step 5: Permission Set
```bash
sudo chmod -R 755 /var/www/html/srm-panel
sudo chmod -R 777 /var/www/html/srm-panel/uploads
```

---

### 🔹 Step 6: Apache Restart
```bash
sudo systemctl restart apache2
```

---

## 🌐 Access Panel

Browser এ গিয়ে ওপেন করো:

```
http://localhost/srm-panel
```

👉 যদি VPS / Server হয়:
```
http://YOUR_SERVER_IP/srm-panel
```

👉 Custom Domain হলে:
```
http://your-domain.com/srm-panel
```

---

## 🔐 Security Tips
- 🔑 Default password change করো  
- 🔒 HTTPS enable করো (Let's Encrypt)  
- ❌ Public access limit করো  
- 📁 Sensitive config file protect করো  

---

## 📁 Project Structure
```
├── index.php
├── config.php
├── action.php
├── functions/
├── views/
├── assets/
└── uploads/
```

---

## 🧩 Customization
- UI → `assets/`  
- Logic → `functions/`  
- Settings → `config.php`

---

## ⚠️ Disclaimer
This project is for **educational & personal use only**.  
Any misuse or unauthorized access is strictly discouraged.

---

## ❤️ Support
- ⭐ Star the repository  
- 🍴 Fork and customize  

---

## 📌 Version
**v11.106.0.11 - Stable Release**

---

## 🔥 Credits
Developed with ❤️ by **Ottking Team (Anirban)**

---
