# Redeploy Google Apps Script - Updated Code

Your Google Apps Script needs to be redeployed with the new changes that include Password and Access fields.

---

## Step-by-Step Instructions

### Step 1: Go to Google Apps Script
1. Open: https://script.google.com
2. Find and open **"Signature Referrals Backend"** project

### Step 2: Replace All Code
1. Delete all the code in the editor (Ctrl+A to select all, then delete)
2. Copy and paste the ENTIRE code below:

```javascript
// Signature Referrals Portal - Google Apps Script Backend
// Deploy as a Web App with "Execute as" your account and "Who has access" set to "Anyone"

const SHEET_ID = '11pQmeeI9ze5XLRB_ho5g1g9DaDL5cjcmbCDygGPGwpc';
const REFERRALS_SHEET = 'Referrals';
const USERS_SHEET = 'Users';

// Set up sheets on first run
function setupSheets() {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  
  // Create Referrals sheet if it doesn't exist
  if (!ss.getSheetByName(REFERRALS_SHEET)) {
    ss.insertSheet(REFERRALS_SHEET);
    const refSheet = ss.getSheetByName(REFERRALS_SHEET);
    refSheet.appendRow(['Customer Name', 'Phone', 'Business', 'Email', 'Notes', 'Status', 'Created At', 'Last Updated']);
  }
  
  // Create Users sheet if it doesn't exist
  if (!ss.getSheetByName(USERS_SHEET)) {
    ss.insertSheet(USERS_SHEET);
    const userSheet = ss.getSheetByName(USERS_SHEET);
    userSheet.appendRow(['Contact Name', 'Email', 'Password', 'Phone', 'Business Name', 'ABN', 'Notes', 'Access', 'Last Updated']);
  }
}

// Main entry point for POST and GET requests
function doPost(e) {
  try {
    const params = JSON.parse(e.postData.contents);
    
    if (params.action === 'addReferral') {
      return addReferral(params);
    } else if (params.action === 'updateReferral') {
      return updateReferral(params);
    } else if (params.action === 'deleteReferral') {
      return deleteReferral(params);
    } else if (params.action === 'saveUser') {
      return saveUser(params);
    } else {
      return createResponse(false, 'Unknown action');
    }
  } catch (error) {
    return createResponse(false, 'Error: ' + error.toString());
  }
}

function doGet(e) {
  try {
    if (e.parameter.action === 'getReferrals') {
      return getReferrals();
    } else if (e.parameter.action === 'getUsers') {
      return getUsers();
    } else {
      return createResponse(false, 'Unknown action');
    }
  } catch (error) {
    return createResponse(false, 'Error: ' + error.toString());
  }
}

// Add a new referral
function addReferral(params) {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(REFERRALS_SHEET);
  
  const row = [
    params.name || '',
    params.phone || '',
    params.business || '',
    params.email || '',
    params.notes || '',
    params.status || 'pending',
    new Date().toISOString(),
    new Date().toISOString()
  ];
  
  sheet.appendRow(row);
  return createResponse(true, 'Referral added successfully');
}

// Update an existing referral
function updateReferral(params) {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(REFERRALS_SHEET);
  
  const data = sheet.getDataRange().getValues();
  let found = false;
  
  for (let i = 1; i < data.length; i++) {
    // Match by customer name and phone (unique identifier)
    if (data[i][0] === params.name && data[i][1] === params.phone) {
      sheet.getRange(i + 1, 1, 1, 8).setValues([[
        params.name || '',
        params.phone || '',
        params.business || '',
        params.email || '',
        params.notes || '',
        params.status || 'pending',
        data[i][6], // Keep original created date
        new Date().toISOString() // Update last modified
      ]]);
      found = true;
      break;
    }
  }
  
  if (found) {
    return createResponse(true, 'Referral updated successfully');
  } else {
    return createResponse(false, 'Referral not found');
  }
}

// Delete (soft delete) a referral by marking as trash
function deleteReferral(params) {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(REFERRALS_SHEET);
  
  const data = sheet.getDataRange().getValues();
  let found = false;
  
  for (let i = 1; i < data.length; i++) {
    if (data[i][0] === params.name && data[i][1] === params.phone) {
      // Set status to 'trash'
      sheet.getRange(i + 1, 6).setValue('trash');
      found = true;
      break;
    }
  }
  
  if (found) {
    return createResponse(true, 'Referral marked as trash');
  } else {
    return createResponse(false, 'Referral not found');
  }
}

// Save or update user profile - USER-SPECIFIC by EMAIL
// Each user's email is unique, so when a user logs in and updates their profile,
// only THEIR row in the Users sheet is updated
function saveUser(params) {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(USERS_SHEET);
  
  // Email is the unique identifier - each logged-in user has their own email
  const userEmail = params.email;
  
  if (!userEmail) {
    return createResponse(false, 'Email is required to save user profile');
  }
  
  const data = sheet.getDataRange().getValues();
  let found = false;
  
  // Find the row for THIS user by matching their email (column B, index 1)
  for (let i = 1; i < data.length; i++) {
    if (data[i][1] === userEmail) {
      // Update ONLY this user's row with their latest profile data
      // Columns: A=Contact Name, B=Email, C=Password, D=Phone, E=Business, F=ABN, G=Notes, H=Access, I=Last Updated
      sheet.getRange(i + 1, 1, 1, 9).setValues([[
        params.name || '',
        userEmail, // Email stays the same (unique identifier)
        params.password || '', // Password (column C, index 2)
        params.phone || '',
        params.business || '',
        params.abn || '',
        params.notes || '',
        params.access || 'Client', // Access level: 'Client' or 'Admin' (column H, index 7)
        new Date().toISOString()
      ]]);
      found = true;
      break;
    }
  }
  
  if (!found) {
    // This is a new user - create a new row for them
    sheet.appendRow([
      params.name || '',
      userEmail,
      params.password || '',
      params.phone || '',
      params.business || '',
      params.abn || '',
      params.notes || '',
      params.access || 'Client', // Default access is 'Client'
      new Date().toISOString()
    ]);
  }
  
  return createResponse(true, 'User profile saved successfully');
}

// Get all referrals (excluding trash)
function getReferrals() {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(REFERRALS_SHEET);
  
  const data = sheet.getDataRange().getValues();
  const referrals = [];
  
  // Skip header row (row 0)
  for (let i = 1; i < data.length; i++) {
    const row = data[i];
    // Skip rows marked as trash
    if (row[5] !== 'trash') {
      referrals.push({
        name: row[0],
        phone: row[1],
        business: row[2],
        email: row[3],
        notes: row[4],
        status: row[5],
        createdAt: row[6],
        lastUpdated: row[7]
      });
    }
  }
  
  return createResponse(true, 'Referrals retrieved', referrals);
}

// Get all users
function getUsers() {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(USERS_SHEET);
  
  const data = sheet.getDataRange().getValues();
  const users = [];
  
  // Skip header row (row 0)
  for (let i = 1; i < data.length; i++) {
    const row = data[i];
    users.push({
      name: row[0],
      email: row[1],
      password: row[2],
      phone: row[3],
      business: row[4],
      abn: row[5],
      notes: row[6],
      access: row[7],
      lastUpdated: row[8]
    });
  }
  
  return createResponse(true, 'Users retrieved', users);
}

// Helper function to create response
function createResponse(success, message, data = null) {
  const response = {
    success: success,
    message: message
  };
  
  if (data !== null) {
    response.data = data;
  }
  
  return ContentService.createTextOutput(JSON.stringify(response))
    .setMimeType(ContentService.MimeType.JSON);
}
```

### Step 3: Save the Code
Click **"Save"** (Ctrl+S)

### Step 4: Delete Old Deployment
1. Click **"Deploy"** button
2. Click **"Manage deployments"**
3. Click the three dots (⋮) next to the current deployment
4. Click **"Delete"**
5. Confirm deletion

### Step 5: Create New Deployment
1. Click **"Deploy"** button again
2. Click **"New Deployment"**
3. Select type: **"Web app"**
4. Execute as: Your email
5. Who has access: **"Anyone"** ⭐ (IMPORTANT!)
6. Click **"Deploy"**
7. A popup shows your new deployment URL
8. **COPY the new URL**

### Step 6: Update Your Application
1. Go to your `index.html` file
2. Find the line with: `const GAS_ENDPOINT = 'https://script.googleapis.com/...'`
3. Replace it with your NEW deployment URL
4. Save and push to GitHub

---

## ✅ After Redeployment

When you save your profile in the app, it will now correctly write to:
- A: Contact Name
- B: Email
- C: Password (NEW)
- D: Phone
- E: Business Name
- F: ABN
- G: Notes
- H: Access (NEW)
- I: Last Updated

---

**This is important!** The old deployment was using the old code structure. Redeploying ensures it uses the new 9-column structure.
