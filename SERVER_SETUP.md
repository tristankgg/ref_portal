# Server-Side JSON Database Setup

This guide will set up your PHP backend to store referrals and users data as JSON files on your tgnet.com.au server.

---

## Directory Structure

Create this folder structure on your server:

```
tgnet.com.au/
├── ref_portal/          (your website)
│   ├── index.html
│   └── ...
└── database/            (NEW - database backend)
    ├── api.php          (main API file)
    └── data/            (JSON database files - created automatically)
        ├── referrals.json
        └── users.json
```

---

## Step 1: Create Database Folder

1. Connect to your server via FTP/SSH
2. Navigate to `tgnet.com.au/`
3. Create a new folder called `database`
4. Inside `database/`, create another folder called `data`

---

## Step 2: Upload API File

1. Upload the `api.php` file to: `tgnet.com.au/database/api.php`
2. Set permissions to `644` (readable by everyone, writable only by owner)

---

## Step 3: Set Folder Permissions

Make sure the `data/` folder is writable:

**Via FTP:**
- Right-click `data/` folder
- Properties → Permissions
- Set to `755`

**Via SSH:**
```bash
chmod 755 /home/username/public_html/database/data
```

---

## Step 4: Test the API

Open in your browser:
```
https://tgnet.com.au/database/api.php?action=getReferrals
```

You should see:
```json
{
  "success": true,
  "message": "Referrals retrieved",
  "data": []
}
```

If you see this, the API is working! ✅

---

## Step 5: Update Your Website

Update your `index.html` to use the PHP backend instead of Google Apps Script.

The API endpoint will be:
```
https://tgnet.com.au/database/api.php
```

---

## API Endpoints

### Add Referral
```
POST https://tgnet.com.au/database/api.php
Body: {
  "action": "addReferral",
  "name": "John Doe",
  "phone": "1234567890",
  "business": "ABC Company",
  "email": "john@example.com",
  "notes": "Great prospect",
  "status": "pending"
}
```

### Update Referral
```
POST https://tgnet.com.au/database/api.php
Body: {
  "action": "updateReferral",
  "name": "John Doe",
  "phone": "1234567890",
  "business": "XYZ Company",
  "status": "completed"
}
```

### Delete Referral (Soft Delete)
```
POST https://tgnet.com.au/database/api.php
Body: {
  "action": "deleteReferral",
  "name": "John Doe",
  "phone": "1234567890"
}
```

### Get All Referrals
```
GET https://tgnet.com.au/database/api.php?action=getReferrals
```

### Save User Profile
```
POST https://tgnet.com.au/database/api.php
Body: {
  "action": "saveUser",
  "name": "Tristan Gallagher",
  "email": "tristankgg@gmail.com",
  "password": "Password123!",
  "phone": "0412345678",
  "business": "Kitchen Installation Co.",
  "abn": "12345678901",
  "notes": "Owner",
  "access": "Admin"
}
```

### Get All Users
```
GET https://tgnet.com.au/database/api.php?action=getUsers
```

---

## JSON File Structure

### referrals.json
```json
[
  {
    "id": "unique_id_123",
    "name": "John Doe",
    "phone": "1234567890",
    "business": "ABC Company",
    "email": "john@example.com",
    "notes": "Great prospect",
    "status": "pending",
    "createdAt": "2025-04-15T10:30:00+00:00",
    "lastUpdated": "2025-04-15T10:30:00+00:00"
  }
]
```

### users.json
```json
[
  {
    "name": "Tristan Gallagher",
    "email": "tristankgg@gmail.com",
    "password": "Password123!",
    "phone": "0412345678",
    "business": "Kitchen Installation Co.",
    "abn": "12345678901",
    "notes": "Owner",
    "access": "Admin",
    "lastUpdated": "2025-04-15T10:30:00+00:00"
  }
]
```

---

## Troubleshooting

### "Permission denied" error
- Check folder permissions (should be 755 for `data/`)
- File permissions (should be 644 for PHP files)

### "Cannot create file" error
- Make sure `data/` folder exists and is writable
- Try creating a test file manually in that folder

### No data appearing
- Check that `referrals.json` and `users.json` exist
- If not, the API should create them automatically on first use

### 404 error
- Make sure `api.php` is in the correct location: `tgnet.com.au/database/api.php`
- Check the exact URL in your browser

---

## Next Steps

Once the API is working:
1. Update the JavaScript in your website to use the PHP API endpoint
2. Replace Google Apps Script calls with server API calls
3. Test by adding a referral and checking the JSON files

The JavaScript changes will be minimal - just change the endpoint URL!

---

## Security Notes

- The `data/` folder should be **outside** the public web folder if possible
- Consider adding authentication/token validation for production
- Never expose sensitive data in JSON files (passwords could be hashed)
- Use HTTPS (your site should already have SSL)

---

**Your server is now ready to store data locally!** 🚀
