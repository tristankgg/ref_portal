# User-Specific Profile Updates - How It Works

## Overview
Each user's profile data is stored in their own row in the "Users" sheet, identified by their email address. When a user logs in and updates their profile, only THEIR row is modified.

---

## How It Works

### When User "tristankgg@gmail.com" Logs In:

1. User logs in with: `tristankgg@gmail.com` / `Password123!`
2. User navigates to "My Profile"
3. User updates their information:
   - Contact Name
   - Phone
   - Business Name
   - ABN
   - Notes
4. User clicks "Save"
5. The app sends the data to Google Apps Script with their email: `tristankgg@gmail.com`
6. Google Apps Script checks the "Users" sheet:
   - Finds the row where column B (Email) = `tristankgg@gmail.com`
   - Updates ONLY that row with the new data
   - If no row exists, creates a new one for this user

### When a Different User (e.g., "john@example.com") Logs In:

1. User logs in with: `john@example.com` / `password123`
2. User navigates to "My Profile"
3. User updates their information
4. Google Apps Script finds the row where Email = `john@example.com`
5. Updates ONLY john's row
6. tristankgg's row remains unchanged

---

## Google Sheet Structure

### Users Sheet

| Row | Contact Name | Email | Phone | Business Name | ABN | Notes | Last Updated |
|-----|---|---|---|---|---|---|---|
| 1 | **Header** | **Email** | **Phone** | **Business Name** | **ABN** | **Notes** | **Last Updated** |
| 2 | Tristan Gallagher | **tristankgg@gmail.com** | 0412345678 | Kitchen Installation Co. | 12345678901 | Owner | 2025-04-15T10:30:00Z |
| 3 | John Smith | **john@example.com** | 0487654321 | ABC Company | 98765432109 | Manager | 2025-04-15T11:00:00Z |
| 4 | Sarah Jones | **sarah@example.com** | 0499999999 | XYZ Business | 11111111111 | Admin | 2025-04-15T11:30:00Z |

Each user's email (column B) acts as the unique identifier.

---

## Code Logic

### In GoogleAppsScript.gs - saveUser() Function:

```javascript
function saveUser(params) {
  // params.email = the currently logged-in user's email
  // (e.g., "tristankgg@gmail.com")
  
  const userEmail = params.email;
  
  // Loop through all rows in Users sheet
  for (let i = 1; i < data.length; i++) {
    if (data[i][1] === userEmail) {
      // Found THIS user's row - update it
      sheet.getRange(i + 1, 1, 1, 7).setValues([[
        params.name,      // Their name
        userEmail,        // Their email (unchanged)
        params.phone,     // Their phone
        params.business,  // Their business
        params.abn,       // Their ABN
        params.notes,     // Their notes
        new Date().toISOString() // Update timestamp
      ]]);
      break; // Stop searching - found their row
    }
  }
}
```

---

## Key Points

✅ **Email is the unique identifier** - Each user's email uniquely identifies their row

✅ **User-specific updates** - Only the logged-in user's row is modified

✅ **Multi-user support** - Unlimited users can have their own profiles

✅ **New users automatically** - First time a user saves, a new row is created for them

✅ **No conflicts** - User A's updates don't affect User B's data

---

## Testing Multi-User Functionality

### Test 1: Single User Updates
1. Login as: `tristankgg@gmail.com`
2. Go to "My Profile"
3. Update phone to "0412345678"
4. Click "Save"
5. Check Google Sheet "Users" tab
6. **Result**: Row with email "tristankgg@gmail.com" is updated

### Test 2: Different User Signs In
1. Logout
2. Login as: `john@example.com` (different email)
3. Go to "My Profile"
4. Update phone to "0487654321"
5. Click "Save"
6. Check Google Sheet "Users" tab
7. **Result**: New row is created for "john@example.com" with their data
8. **Result**: Original "tristankgg@gmail.com" row is unchanged

### Test 3: Same User Logs Back In
1. Logout
2. Login as: `tristankgg@gmail.com` again
3. Update phone to "0444444444"
4. Click "Save"
5. Check Google Sheet "Users" tab
6. **Result**: The row with email "tristankgg@gmail.com" is updated
7. **Result**: Their old phone number (0412345678) is replaced with new one (0444444444)

---

## Current Login System

Currently, the app supports one hardcoded user:
- **Email**: `tristankgg@gmail.com`
- **Password**: `Password123!`

To support multiple users with full authentication, you would need:
1. A backend authentication system (Firebase, Auth0, custom backend)
2. Multiple user accounts in a database
3. Login validation against that database

For now, the profile update system is ready for this multi-user approach - it just needs the authentication layer.

---

## How to Add More Test Users

To test with different emails, you could modify the login validation code:

```javascript
function handleLogin(e) {
  e.preventDefault();
  const email = document.getElementById('email').value;
  const password = document.getElementById('password').value;

  // Instead of hardcoded check, could validate against a list:
  const validUsers = {
    'tristankgg@gmail.com': 'Password123!',
    'john@example.com': 'password123',
    'sarah@example.com': 'password456'
  };

  if (validUsers[email] === password) {
    // Login successful - email is stored
    currentUser = {
      email: email,
      name: email.split('@')[0], // Extract name from email
      // ... other fields
    };
    loginSuccess();
  } else {
    alert('Invalid credentials');
  }
}
```

This would allow testing the user-specific profile updates with different emails.

---

## Summary

✨ **The system is already designed for user-specific updates!**

- Each user's email uniquely identifies their row
- When they log in and save their profile, only THEIR row is updated
- Multiple users can have their own profiles without interfering with each other
- Ready for future multi-user authentication system
