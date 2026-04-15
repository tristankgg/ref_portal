# Troubleshooting: Google Sheet Not Updating

## Quick Checklist

### ❌ Problem: Nothing appears in Google Sheet

Follow these steps in order:

---

## Step 1: Check Your Google Sheet Structure

### Required Sheets (Tabs)
Your Google Sheet MUST have these two tabs:

**Tab 1: "Referrals"**
- Go to your sheet
- Look at the bottom where it shows tab names
- Do you see a tab called exactly **"Referrals"**?
  - ✅ If YES → Go to Step 2
  - ❌ If NO → Create it:
    1. Click **"+"** button at bottom left
    2. Name it **"Referrals"** (case-sensitive!)
    3. Click **"Create"**

**Tab 2: "Users"**
- Do you see a tab called exactly **"Users"**?
  - ✅ If YES → Go to Step 2
  - ❌ If NO → Create it:
    1. Click **"+"** button at bottom left
    2. Name it **"Users"** (case-sensitive!)
    3. Click **"Create"**

---

## Step 2: Check Column Headers

### Referrals Tab Headers
1. Click on the **"Referrals"** tab
2. Look at row 1 (the first row)
3. You should see these exact headers:
   ```
   A1: Customer Name
   B1: Phone
   C1: Business
   D1: Email
   E1: Notes
   F1: Status
   G1: Created At
   H1: Last Updated
   ```

**If headers are missing:**
1. Click on cell A1
2. Type: `Customer Name`
3. Press Tab, type: `Phone`
4. Press Tab, type: `Business`
5. Press Tab, type: `Email`
6. Press Tab, type: `Notes`
7. Press Tab, type: `Status`
8. Press Tab, type: `Created At`
9. Press Tab, type: `Last Updated`
10. Press Enter

### Users Tab Headers
1. Click on the **"Users"** tab
2. Look at row 1
3. You should see these exact headers:
   ```
   A1: Contact Name
   B1: Email
   C1: Phone
   D1: Business Name
   E1: ABN
   F1: Notes
   G1: Last Updated
   ```

**If headers are missing:**
1. Click on cell A1
2. Type: `Contact Name`
3. Press Tab, type: `Email`
4. Press Tab, type: `Phone`
5. Press Tab, type: `Business Name`
6. Press Tab, type: `ABN`
7. Press Tab, type: `Notes`
8. Press Tab, type: `Last Updated`
9. Press Enter

---

## Step 3: Check Google Apps Script Deployment

1. Go to: https://script.google.com
2. Look for a project called **"Signature Referrals Backend"**
3. Open it
4. Look at the top - you should see **"Deployments"** section
5. Click on **"Deployments"** (it should show "2 deployments" or similar)
6. Look for the deployment that says **"Web app"**
7. Check the details:
   - ✅ Execution: Should show your email
   - ✅ Execute as: Should be your email
   - ✅ Who has access: Should say **"Anyone"**

**If "Who has access" is NOT "Anyone":**
1. Click the three dots (⋮) next to the deployment
2. Click **"Edit"**
3. Change "Who has access" to **"Anyone"**
4. Click **"Update"**
5. Go back and verify it now says **"Anyone"**

---

## Step 4: Test the Deployment

1. Go to your Google Apps Script project
2. Look for a function called **`addReferral`**
3. Select it from the dropdown at the top (where it says "Select function")
4. Click the play button (▶️) to run it
5. You should see an error popup or success message
6. Check if it gives you any clues about what's wrong

---

## Step 5: Check Browser Console for Errors

1. Open your application
2. Press **F12** to open Developer Tools
3. Click on the **"Console"** tab
4. Try adding a referral
5. Look for any red error messages
6. Take a screenshot of any errors and share them

Common errors:
- **"Cannot find sheet"** → Sheets named wrong
- **"Permission denied"** → Apps Script not deployed correctly
- **"Cannot read property"** → Headers in wrong position

---

## Step 6: Verify the Google Apps Script Code

1. Go to: https://script.google.com
2. Open **"Signature Referrals Backend"**
3. Check that line 4 has your Sheet ID:
   ```javascript
   const SHEET_ID = '11pQmeeI9ze5XLRB_ho5g1g9DaDL5cjcmbCDygGPGwpc';
   ```
4. Check that line 5 says:
   ```javascript
   const REFERRALS_SHEET = 'Referrals';
   ```
5. Check that line 6 says:
   ```javascript
   const USERS_SHEET = 'Users';
   ```

**If these are wrong:**
1. Fix them
2. Click **"Save"**
3. Go to **"Deploy"** → **"Manage deployments"**
4. Click the three dots next to the Web app deployment
5. Click **"Delete"**
6. Click **"Deploy"** → **"New Deployment"**
7. Select type: **"Web app"**
8. Execute as: Your email
9. Who has access: **"Anyone"**
10. Click **"Deploy"**
11. Copy the new deployment URL
12. Update index.html with the new URL

---

## Step 7: Manual Test

1. In Google Apps Script, click **"Run"** button
2. It might ask for permissions - click **"Review permissions"** and **"Allow"**
3. Check if any function runs successfully
4. Go to your Google Sheet
5. Manually refresh (Ctrl+R or Cmd+R)
6. Do you see any new rows appear?

---

## Complete Diagnostic Checklist

Before testing again, verify ALL of these:

- [ ] "Referrals" tab exists (exact name)
- [ ] "Users" tab exists (exact name)
- [ ] Referrals tab has 8 column headers (A-H)
- [ ] Users tab has 7 column headers (A-G)
- [ ] Google Apps Script deployment exists
- [ ] Deployment is set to "Anyone" access
- [ ] Sheet ID in GoogleAppsScript.gs is correct
- [ ] index.html has correct GAS_ENDPOINT URL
- [ ] No errors in browser console (F12)

---

## Still Not Working?

If it's still not updating after all these steps:

1. **Open browser console (F12)**
2. **Try adding a referral**
3. **Look for error messages** (they'll be in red)
4. **Share the exact error message** from the console
5. **Share a screenshot** of your Google Sheet tabs and headers

This will help diagnose the issue!

---

## Common Issues & Solutions

### Issue: "Cannot find sheet: Referrals"
**Solution**: 
- The sheet tab name is wrong (case-sensitive!)
- Make sure it's exactly "Referrals" not "referrals" or "REFERRALS"

### Issue: "Script function not found"
**Solution**:
- Google Apps Script code wasn't pasted correctly
- Re-paste the entire GoogleAppsScript.gs code again

### Issue: "Permission denied"
**Solution**:
- Deployment not set to "Anyone"
- Re-deploy with correct permissions

### Issue: "Sheet is empty (no columns)"
**Solution**:
- Headers are missing from row 1
- Add all the required headers manually

### Issue: Sheet ID error
**Solution**:
- The Sheet ID in the code doesn't match your actual Sheet
- Copy your Sheet ID from the URL and update the code

---

## How to Verify It's Working

Once you've fixed everything:

1. Open your app
2. Login
3. Add a test referral with:
   - Name: "Test"
   - Phone: "1234567890"
   - Business: "Test Co"
4. Click "Add"
5. You should see: "Referral added successfully"
6. Go to your Google Sheet
7. Click on the "Referrals" tab
8. You should see your test data in row 2 (row 1 is headers)
9. If you see it → It's working! 🎉

---

## Final Troubleshooting Command

If you want me to help, tell me:
1. Do you see the "Referrals" and "Users" tabs?
2. Do you see the column headers in those tabs?
3. What error message appears in the browser console (F12)?
4. Does the app show "Success" when you add a referral?

This info will help me solve it!
