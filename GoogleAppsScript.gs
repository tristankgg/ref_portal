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
    userSheet.appendRow(['Contact Name', 'Email', 'Phone', 'Business Name', 'ABN', 'Notes', 'Last Updated']);
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

// Save or update user profile
function saveUser(params) {
  const ss = SpreadsheetApp.openById(SHEET_ID);
  const sheet = ss.getSheetByName(USERS_SHEET);
  
  const data = sheet.getDataRange().getValues();
  let found = false;
  
  // Check if user already exists (by email)
  for (let i = 1; i < data.length; i++) {
    if (data[i][1] === params.email) {
      // Update existing user
      sheet.getRange(i + 1, 1, 1, 7).setValues([[
        params.name || '',
        params.email || '',
        params.phone || '',
        params.business || '',
        params.abn || '',
        params.notes || '',
        new Date().toISOString()
      ]]);
      found = true;
      break;
    }
  }
  
  if (!found) {
    // Add new user
    sheet.appendRow([
      params.name || '',
      params.email || '',
      params.phone || '',
      params.business || '',
      params.abn || '',
      params.notes || '',
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
      phone: row[2],
      business: row[3],
      abn: row[4],
      notes: row[5],
      lastUpdated: row[6]
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
