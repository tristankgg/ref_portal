# Google Sheets Integration Setup Guide

This guide will help you set up Google Sheets to store your referrals and user data.

## Step 1: Create a Google Sheet

1. Go to https://sheets.google.com
2. Click **"+ Create"** or **"New"**
3. Choose **"Blank spreadsheet"**
4. Name it **"Signature Referrals"** (or your preferred name)

## Step 2: Create Sheet Tabs

In your spreadsheet, create two sheets (tabs):

### Sheet 1: "Referrals"
Add these column headers in the first row:
```
A: Customer Name
B: Phone
C: Business
D: Email
E: Notes
F: Status
```

### Sheet 2: "Users"
Add these column headers in the first row:
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
3. Copy the `{SHEET_ID}` part (long string of characters between `/d/` and `/edit`)
4. Save this for the next step

## Step 4: Set Up Google Sheets API

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project:
   - Click the project dropdown at the top
   - Click **"NEW PROJECT"**
   - Name it "Signature Referrals Portal"
   - Click **"CREATE"**

3. Enable the Sheets API:
   - In the search bar at the top, type **"Google Sheets API"**
   - Click on it
   - Click **"ENABLE"**

4. Create API Credentials:
   - Click **"CREATE CREDENTIALS"** (blue button)
   - Select **"API key"** from the dropdown
   - Your API key will be displayed
   - Copy and save this key

## Step 5: Share Your Google Sheet

1. Open your Google Sheet
2. Click **"Share"** button (top right)
3. In the "Get link" section, set it to **"Anyone with the link can view"**
4. Copy the link (you'll see the SHEET_ID in it)

## Step 6: Update the Application

1. Open `index.html` in a text editor
2. Find these lines (around line 1155):
   ```javascript
   const SHEET_ID = 'YOUR_SHEET_ID_HERE';
   const API_KEY = 'YOUR_API_KEY_HERE';
   ```

3. Replace with your actual values:
   ```javascript
   const SHEET_ID = 'paste-your-sheet-id-here';
   const API_KEY = 'paste-your-api-key-here';
   ```

4. Save the file

## Step 7: Test the Integration

1. Open the application
2. Login with the test credentials
3. Try adding a referral
4. Check your Google Sheet - the referral should appear!
5. Go to "My Profile" and save your profile
6. Check the "Users" sheet - your data should appear!

## Troubleshooting

### "Permission Denied" Error
- Make sure your Google Sheet is shared with "Anyone with the link can view"
- Verify your API Key is correct
- Check that the Google Sheets API is enabled in Google Cloud Console

### Data not appearing in Sheet
- Verify the sheet tab names are exactly "Referrals" and "Users"
- Check that column headers are in row 1
- Look at the browser console (F12 → Console) for error messages

### Getting SHEET_ID
- It's the long string in the URL between `/d/` and `/edit`
- Example: `https://docs.google.com/spreadsheets/d/1abc123xyz456/edit`
- The SHEET_ID is: `1abc123xyz456`

## API Limitations

- Free tier allows up to 300 requests per minute
- Read requests are typically faster than writes
- The integration uses the Google Sheets API v4

## Security Notes

- **IMPORTANT**: Your API Key is visible in the HTML source. Anyone who inspects the page can see it.
- For production use, consider using a backend server to handle API calls
- Alternatively, use OAuth 2.0 for better security (more complex setup)

## Support

For issues with:
- **Google Sheets**: Check Google Sheets Help
- **Google Cloud Console**: Check GCP Documentation
- **Application**: Check the browser console for error messages (F12 → Console tab)

---

Once set up, your referrals will automatically sync to Google Sheets!
