# Application Code Manager for Fluent Forms

A custom WordPress plugin developed to manage and validate one-time application codes before users can access or submit a Fluent Form. It ensures that only applicants with valid, unused codes can proceed with form submission. Once a form is submitted, the code is marked as used and cannot be reused.

> 🏫 Originally developed for **[Wesley University, Lagos Centre](https://wesleyuniversitylagoscentre.com/register)** as part of their online application system.

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

&lt;div id="verify-code-wrapper"&gt;
  &lt;p&gt;&lt;b&gt;Note that Admission form costs ₦5,000.&lt;/b&gt;&lt;br&gt;
  Click the button below to pay and fill your Admission Form or Fill the form if you have already been given an APPLICATION CODE.&lt;br&gt;
  Contact 00000000 or 00000000 if you need any assistance with the process.&lt;br&gt;
  &lt;a href="https://paystack.shop/pay"&gt;&lt;button class="buy-form"&gt;BUY FORM&lt;/button&gt;&lt;/a&gt;&lt;/p&gt;

  &lt;label for="application_code"&gt;Enter Application Code:&lt;/label&gt;
  &lt;input type="text" name="application_code" required&gt;

  &lt;label for="full_name"&gt;Your Full Name:&lt;/label&gt;
  &lt;input type="text" name="full_name" required&gt;

  &lt;button id="verify-code-btn" type="button"&gt;Verify Code&lt;/button&gt;

  &lt;div id="verify-loader" style="display: none;"&gt;Verifying...&lt;/div&gt;
  &lt;div id="application-code-error" style="display: none; color: red;"&gt;&lt;/div&gt;
&lt;/div&gt;


---
## HTML Output Sample

<div id="verify-code-wrapper">
  <p><b>Note that Admission form costs ₦5,000.</b><br>
  Click the button below to pay and fill out your Admission Form, or fill out the form if you have already been given an APPLICATION CODE <br>
  Contact 00000000000 or 0000000000 if you need any assistance with the process.<br>
  <a href="https://madebydami.com"><button class="buy-form">BUY FORM</button></a></p>

  <label for="application_code">Enter Application Code:</label>
  <input type="text" name="application_code" required>

  <label for="full_name">Your Full Name:</label>
  <input type="text" name="full_name" required>

  <button id="verify-code-btn" type="button">Verify Code</button>

  <div id="verify-loader" style="display: none;">Verifying...</div>
  <div id="application-code-error" style="display: none; color: red;"></div>
</div>

---

## CSS USED

#verify-code-btn {
    margin-top: 10px;
    border-radius: 0px;
    background-color: #e83831;
    font-weight: 600;
}

.buy-form {
    margin-top: 5px;
    border-radius: 0px;
    background-color: #0a3150;
    font-weight: 600;
}

label, legend {
    color: #0a3150;
    font-size: 16px;
    font-style: normal;
    font-weight: 600;
    line-height: 20px;
    margin-bottom: 5px 
    
}

input[type="text"], input[type="number"], input[type="email"], input[type="url"], input[type="password"], input[type="search"], input[type=reset], input[type=tel], input[type=date], select, textarea {
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 24px;
    width: 100%;
    height: 45px;
    padding: 14px 16px;
    border-radius: 0;
    margin-top: 4px;
    
    color: var(--ast-form-input-text, #475569);
}


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
🔗 [Portfolio](https://madebydami.com)  
🔗 [Calendly](https://calendly.com/hajidamilola91/30min)

---

## 📄 License

GPLv2 or later  
[View License](https://www.gnu.org/licenses/gpl-2.0.html)

---

## 📌 Note

This project was originally built for a real-world use case and is now shared as part of my portfolio to demonstrate custom WordPress plugin development with Fluent Forms integration.
