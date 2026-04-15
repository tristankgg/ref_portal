# Google Apps Script Integration Guide

This guide will set up the Signature Referrals Portal to save data to Google Sheets using Google Apps Script (the same approach as the Storeman project).

## Benefits of This Approach

✅ **No API Key exposure** - Script handles all authentication
✅ **Simpler setup** - No Google Cloud Console needed
✅ **More reliable** - Google Apps Script is designed for this
✅ **Better security** - API key isn't visible in frontend code
✅ **No CORS issues** - Uses no-cors mode

---

## Step 1: Create a Google Sheet

1. Go to https://sheets.google.com
2. Click **"+ Create"** or **"New"**
3. Choose **"Blank spreadsheet"**
4. Name it **"Signature Referrals Database"**

## Step 2: Create Sheet Tabs

In your new spreadsheet, create two sheets (tabs):

### Sheet 1: "Referrals"
Click the **"+"** at the bottom to add a new sheet. Name it **"Referrals"**

Add these headers in row 1:
```
A: Customer Name
B: Phone
C: Business
D: Email
E: Notes
F: Status
G: Created At
H: Last Updated
```

### Sheet 2: "Users"
Add another sheet named **"Users"**

Add these headers in row 1:
```
A: Contact Name
B: Email
C: Phone
D: Business Name
E: ABN
F: Notes
G: Last Updated
```

## Step 3: Get Your Sheet ID

1. Open your Google Sheet
2. Look at the URL: `https://docs.google.com/spreadsheets/d/{SHEET_ID}/edit`
3. Copy the SHEET_ID (the long string between `/d/` and `/edit`)
4. **Save this** - you'll need it in Step 5

## Step 4: Create the Google Apps Script

1. Go to https://script.google.com
2. Click **"+ New project"** (or "+ Create" if you don't see projects)
3. Name it **"Signature Referrals Backend"**
4. In the editor, delete all the default code
5. **Copy and paste the entire code from `GoogleAppsScript.gs`**
6. In the code, find this line (near the top):
   ```javascript
   const SHEET_ID = 'YOUR_SHEET_ID_HERE';
   ```
7. Replace `YOUR_SHEET_ID_HERE` with your actual Sheet ID from Step 3
8. Click **"Save"** (Ctrl+S)

## Step 5: Deploy the Google Apps Script

1. Click **"Deploy"** button (top right)
2. Click **"New Deployment"**
3. Select **"Type"** dropdown and choose **"Web app"**
4. Set the following:
   - **Execute as**: (Your email address)
   - **Who has access**: **"Anyone"**
5. Click **"Deploy"**
6. A popup will appear with your deployment URL
7. Copy the URL - it should look like:
   ```
   https://script.googleapis.com/macros/s/{DEPLOYMENT_ID}/usercopy
   ```
8. **IMPORTANT**: Copy this entire URL

## Step 6: Update the Application

1. Open `index.html` in a text editor
2. Find this line (around line 1157):
   ```javascript
   const GAS_ENDPOINT = 'YOUR_GAS_DEPLOYMENT_URL_HERE';
   ```
3. Replace with your deployment URL from Step 5:
   ```javascript
   const GAS_ENDPOINT = 'https://script.googleapis.com/macros/s/YOUR_DEPLOYMENT_ID/usercopy';
   ```
4. **Save the file**
5. **Push to GitHub** (if hosted there)

## Step 7: Test the Integration

1. Open your application (locally or on GitHub Pages)
2. Login with: `tristankgg@gmail.com` / `Password123!`
3. Add a test referral
4. Check your Google Sheet - you should see the data appear in the "Referrals" tab!
5. Go to "My Profile" and update something
6. Check the "Users" tab - your data should appear!

---

## How It Works

### Adding a Referral:
```
User fills form → JavaScript sends data → Google Apps Script receives it → 
Appends to Google Sheet → Success message shown
```

### Saving Profile:
```
User edits profile → JavaScript sends data → Google Apps Script receives it → 
Adds/updates row in Users sheet → Success message shown
```

---

## Troubleshooting

### "App installation is not complete"
- Go back to the deployment
- Click the deployment ID (not "Deploy" button)
- Check "Who has access" is set to "Anyone"
- Try re-deploying if needed

### Data not appearing in Google Sheet
- Check the sheet tab names are **exactly** "Referrals" and "Users"
- Verify headers are in row 1
- Check the SHEET_ID in GoogleAppsScript.gs is correct
- Check browser console (F12 → Console) for errors
- Try refreshing the Google Sheet

### Getting a blank response
- Make sure Google Apps Script is deployed with "Anyone" access
- Check the GAS_ENDPOINT URL in index.html is correct
- Verify the deployment is the latest version

### "400 Bad Request"
- Check the JSON format being sent
- Verify all field names match between frontend and backend
- Look at browser console for details

---

## File Structure

```
ref_portal/
├── index.html (updated with GAS_ENDPOINT)
├── GoogleAppsScript.gs (your backend script)
├── .gitignore
├── README.md
├── GOOGLE_SHEETS_SETUP.md
└── GOOGLE_APPS_SCRIPT_SETUP.md (this file)
```

---

## Key Differences from Direct API

| Aspect | Direct API | Google Apps Script |
|--------|-----------|-------------------|
| API Key in Code | ❌ Visible | ✅ Hidden |
| Setup Complexity | Medium | Simple |
| Authentication | OAuth | Automatic |
| CORS Issues | Yes | No (uses no-cors) |
| Cost | Free | Free |
| Reliability | Good | Better |

---

## Google Sheet Columns Reference

### Referrals Sheet
| Column | Data Type | Example |
|--------|-----------|---------|
| A | Customer Name | John Doe |
| B | Phone | 1234567890 |
| C | Business | ABC Company |
| D | Email | john@example.com |
| E | Notes | Great prospect |
| F | Status | pending |
| G | Created At | 2025-04-15T10:30:00.000Z |
| H | Last Updated | 2025-04-15T10:30:00.000Z |

### Users Sheet
| Column | Data Type | Example |
|--------|-----------|---------|
| A | Contact Name | Tristan Gallagher |
| B | Email | tristankgg@gmail.com |
| C | Phone | 0412345678 |
| D | Business Name | Kitchen Installation Co. |
| E | ABN | 12345678901 |
| F | Notes | Owner and manager |
| G | Last Updated | 2025-04-15T10:30:00.000Z |

---

## Deployment Diagram

```
┌─────────────────────────┐
│   index.html (Frontend) │
│  (Browser JavaScript)   │
└────────────┬────────────┘
             │ POST/GET
             │ (no-cors)
             ▼
┌─────────────────────────┐
│  Google Apps Script     │
│  (Backend)              │
└────────────┬────────────┘
             │ Reads/Writes
             │
             ▼
┌─────────────────────────┐
│   Google Sheet          │
│   (Database)            │
│ - Referrals             │
│ - Users                 │
└─────────────────────────┘
```

---

## Common Questions

**Q: Do I need Google Cloud Console?**
A: No! This approach doesn't need it at all.

**Q: Can multiple people access the app?**
A: Yes, anyone can login. Each person's profile is stored in the Users sheet.

**Q: Is my data secure?**
A: Your data is in your own Google Sheet. Only people with access to that sheet can see it. The Google Apps Script is publicly accessible but doesn't expose data without authentication.

**Q: Can I edit the backend?**
A: Yes! Go to https://script.google.com and edit the code anytime. Click "Deploy" > "Manage deployments" > "New deployment" to update it.

**Q: What if I want to modify the sheet structure?**
A: You can! Just update the column headers in your Google Sheet and update the corresponding code in GoogleAppsScript.gs

---

## Next Steps

1. ✅ Create Google Sheet
2. ✅ Create sheet tabs (Referrals, Users)
3. ✅ Copy your Sheet ID
4. ✅ Create Google Apps Script
5. ✅ Deploy as Web App
6. ✅ Copy deployment URL
7. ✅ Update index.html with URL
8. ✅ Test by adding referrals/profile
9. ✅ Push to GitHub
10. ✅ Done!

---

## Links & Resources

- **Google Sheets**: https://sheets.google.com
- **Google Apps Script**: https://script.google.com
- **This Project**: Your GitHub repository
- **Storeman Project Reference**: Similar implementation

---

**Setup Complete!** Your Signature Referrals Portal is now connected to Google Sheets! 🎉
