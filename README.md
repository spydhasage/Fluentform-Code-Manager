# Application Code Manager for Fluent Forms

A custom WordPress plugin developed to manage and validate one-time application codes before users can access or submit a Fluent Form. It ensures that only applicants with valid, unused codes can proceed with form submission. Once a form is submitted, the code is marked as used and cannot be reused.

> 🏫 Originally developed for **Wesley University, Lagos Centre** as part of their online application system.

---

## 🔧 Features

- ✅ Admin interface to add, view, and delete codes
- ✅ AJAX-based code verification before form access
- ✅ Prevents duplicate or reused code submissions
- ✅ Auto-mark codes as used only after successful form submission
- ✅ Logs the full name of the user who used the code
- ✅ Clean separation of logic with no effect on existing site behaviour

---

## 🛠️ How It Works

1. **Admin uploads codes** via the dashboard (one per line).
2. **User enters full name + code** → clicks "Verify Code".
3. **If valid**, the form fields appear and the code is saved in a hidden field.
4. On **successful submission**, the plugin marks the code as "used" and stores the submitter's name.
5. The code becomes unavailable to future users.

---

## 📥 Installation

1. Upload the plugin folder to `/wp-content/plugins/` or install via the WordPress dashboard.
2. Activate it from the **Plugins** page.
3. Go to **Application Codes** in the admin menu to manage codes.

---

## 🧪 Fluent Forms Setup

In your Fluent Form:

- Add:
  - A text field named `application_code`
  - A text field named `your_name` (or whatever the name field is)
  - A hidden field named `application_code_confirmed`

- Add a **custom section** above the form with:
  - A wrapper div: `#verify-code-wrapper`
  - A verify button: `#verify-code-btn`

The plugin JS handles the AJAX verification and form reveal.

---

## HTML Structure for FrontEnd Use

<div id="verify-code-wrapper">
  <p><b>Note that Admission form costs ₦5,000.</b><br>
  Click the button below to pay and fill your Admission Form or Fill the form if you have already been given an APPLICATION CODE.<br>
  Contact 08113625555 or 08055849779 if you need any assistance with the process.<br>
  <a href="https://paystack.shop/pay/wesley"><button class="buy-form">BUY FORM</button></a></p>

  <label for="application_code">Enter Application Code:</label>
  <input type="text" name="application_code" required>

  <label for="full_name">Your Full Name:</label>
  <input type="text" name="full_name" required>

  <button id="verify-code-btn" type="button">Verify Code</button>

  <div id="verify-loader" style="display: none;">Verifying...</div>
  <div id="application-code-error" style="display: none; color: red;"></div>
</div>


---

## 💻 Technologies Used

- WordPress Plugin API
- Fluent Forms hooks (`fluentform_submission_inserted`)
- jQuery (for frontend interaction)
- AJAX with `admin-ajax.php`
- WordPress Options API

---

## 🧑‍💻 Author

**Damilola Ajila**  
🔗 [Portfolio](https://damilola.online)  
🔗 [Calendly](https://calendly.com/hajidamilola91/30min)

---

## 📄 License

GPLv2 or later  
[View License](https://www.gnu.org/licenses/gpl-2.0.html)

---

## 📌 Note

This project was originally built for a real-world use case and is now shared as part of my portfolio to demonstrate custom WordPress plugin development with Fluent Forms integration.
