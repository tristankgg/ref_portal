# Your Google Apps Script Setup - Quick Reference

## Your Google Sheet Details
- **Sheet URL**: https://docs.google.com/spreadsheets/d/11pQmeeI9ze5XLRB_ho5g1g9DaDL5cjcmbCDygGPGwpc/edit
- **Sheet ID**: `11pQmeeI9ze5XLRB_ho5g1g9DaDL5cjcmbCDygGPGwpc`
- **Status**: ✅ Already set in GoogleAppsScript.gs

---

## Step-by-Step Setup

### Step 1: Verify Your Google Sheet Tabs ✅

Make sure your Google Sheet has these two tabs:

1. **"Referrals"** tab with headers:
   - A: Customer Name
   - B: Phone
   - C: Business
   - D: Email
   - E: Notes
   - F: Status
   - G: Created At
   - H: Last Updated

2. **"Users"** tab with headers:
   - A: Contact Name
   - B: Email
   - C: Phone
   - D: Business Name
   - E: ABN
   - F: Notes
   - G: Last Updated

**If tabs don't exist**, add them now:
- Click **"+"** button at bottom left
- Name the tab "Referrals" or "Users"
- Add the headers in row 1

---

### Step 2: Create Google Apps Script ✅

1. Go to **https://script.google.com**
2. Click **"+ New Project"** (top left)
3. Name it **"Signature Referrals Backend"**
4. Delete the default code
5. **Copy and paste the entire code from `GoogleAppsScript.gs`** (from the GitHub repo)
   - The Sheet ID is already filled in!
6. Click **"Save"** (Ctrl+S or Cmd+S)

---

### Step 3: Deploy the Script ✅

1. Click **"Deploy"** button (top right, blue button)
2. Click **"New Deployment"**
3. Click the **"Select type"** dropdown → Choose **"Web app"**
4. Set:
   - **Execute as**: (Your email - should auto-select)
   - **Who has access**: **"Anyone"**
5. Click **"Deploy"**
6. A dialog appears with your deployment URL
7. **Copy the entire URL** - it looks like:
   ```
   https://script.googleapis.com/macros/s/AKfycbzXXXXXXXXXXXX/usercopy
   ```
8. Click **"Done"** or close the dialog
9. **SAVE THIS URL** - you'll need it in the next step

---

### Step 4: Update Your Application ✅

1. Open `index.html` in a text editor (from GitHub or downloaded)
2. Find this line (around line 1157):
   ```javascript
   const GAS_ENDPOINT = 'YOUR_GAS_DEPLOYMENT_URL_HERE';
   ```
3. Replace with your deployment URL:
   ```javascript
   const GAS_ENDPOINT = 'https://script.googleapis.com/macros/s/AKfycbzXXXXXXXXXXXX/usercopy';
   ```
   (Use your actual URL from Step 3)
4. Save the file
5. If using GitHub, commit and push:
   ```bash
   git add index.html
   git commit -m "Update GAS endpoint URL"
   git push origin main
   ```

---

### Step 5: Test It! ✅

1. Open your application (locally or GitHub Pages)
2. Login with:
   - Email: `tristankgg@gmail.com`
   - Password: `Password123!`
3. **Test 1 - Add a Referral:**
   - Click "Add Referral"
   - Fill in:
     - Customer name: "Test Customer"
     - Phone: "1234567890"
     - Business: "Test Business"
     - Email: (optional)
     - Notes: (optional)
   - Click "Add"
   - Go back to your Google Sheet
   - **You should see the data appear in the "Referrals" tab!**

4. **Test 2 - Save Profile:**
   - Click "My Profile" in the sidebar
   - Edit the fields (or just click Save as-is)
   - Click "Save"
   - Go back to your Google Sheet
   - **You should see data appear in the "Users" tab!**

---

## Troubleshooting

### "App installation is not complete" Error
- Go back to Google Apps Script page
- Click the deployment (the ID next to the script name)
- Click the three dots → Edit
- Check "Who has access" is set to **"Anyone"**
- Click **"Update"** if you made changes

### Data not appearing in Google Sheet
1. **Check Sheet Tab Names:**
   - Make sure tab names are **exactly** "Referrals" and "Users"
   - Tab names are case-sensitive!

2. **Check Headers:**
   - Headers must be in row 1
   - Check spelling and column positions

3. **Check the URL:**
   - Make sure the GAS_ENDPOINT in index.html is correct
   - Make sure it starts with `https://script.googleapis.com/macros/s/`

4. **Check Browser Console:**
   - Open DevTools (F12)
   - Go to "Console" tab
   - Look for any error messages
   - You should see: "Data sent to Google Sheet: {...}"

### Blank Page / Won't Load
- Refresh the page (Ctrl+R or Cmd+R)
- Clear browser cache
- Check browser console for errors

### Permission Denied
- Go to Google Apps Script
- Make sure "Who has access" is set to "Anyone"
- Redeploy if needed

---

## What Happens When You Submit Data

### Adding a Referral:
```
1. You fill the form and click "Add"
2. JavaScript sends the data to your Google Apps Script
3. Google Apps Script appends a row to the "Referrals" sheet
4. You see "Success!" message
5. Your data appears in Google Sheet
```

### Saving Profile:
```
1. You edit profile and click "Save"
2. JavaScript sends the data to your Google Apps Script
3. Google Apps Script finds your email and updates the row in "Users" sheet
4. If email not found, creates a new row
5. You see "Success!" message
6. Your data appears in Google Sheet
```

---

## Your Google Apps Script Code (Already Configured!)

The `GoogleAppsScript.gs` file in the repo has:
- ✅ Your Sheet ID already filled in
- ✅ Functions to add/update referrals
- ✅ Functions to save user profiles
- ✅ Proper error handling
- ✅ Timestamps for all entries

---

## Files You Need

1. **index.html** - Your application (update GAS_ENDPOINT)
2. **GoogleAppsScript.gs** - Backend code (already has your Sheet ID!)
3. Your Google Sheet - Already created!

---

## Quick Links

- **Your Google Sheet**: https://docs.google.com/spreadsheets/d/11pQmeeI9ze5XLRB_ho5g1g9DaDL5cjcmbCDygGPGwpc/
- **Google Apps Script**: https://script.google.com
- **Your GitHub Repo**: https://github.com/tristankgg/ref_portal

---

## Next Actions

1. ✅ Get the GoogleAppsScript.gs from the GitHub repo
2. ✅ Create Google Apps Script project
3. ✅ Paste code (Sheet ID is already in it!)
4. ✅ Deploy as Web App (Anyone can access)
5. ✅ Copy deployment URL
6. ✅ Update index.html GAS_ENDPOINT with URL
7. ✅ Push to GitHub
8. ✅ Test by adding data
9. ✅ Check Google Sheet for data!

---

**Status**: Ready to deploy! 🚀
