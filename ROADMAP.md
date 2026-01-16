# 🧭 Soqqa Controll App — Roadmap

## 🎯 Maqsad
Pul kirim-chiqimini nazorat qilish, auditga tayyor va kengaytiriladigan backend yaratish.

---

## PHASE 0 — Foundation (BOR)
- User register
- User auth (JWT)
- Refresh token
- /users/me
- Error handling (global, custom)

---

## PHASE 1 — Core Money Flow (HOZIRGI MAQSAD)

### Account / Wallet
- POST /accounts
- GET  /accounts
- GET  /accounts/{id}
- GET  /accounts/{id}/balance

### Transaction
- POST /transactions
- GET  /transactions
- GET  /transactions/{id}
- Export transactions (Excel)

### Category
- POST /categories
- GET  /categories

---

## PHASE 2 — Insight & Control
- GET /stats/summary
- GET /stats/by-category
- GET /stats/by-period

---

## PHASE 3 — Management (KELAJAK)
- User management
- Roles & permissions
- Audit log
- Advanced export

---

## PHASE 4 — Advanced (HOZIR YO‘Q)
- Budgets
- Recurring transactions
- Notifications
- Multi-user accounts

---

## Qoida
- Controller = HTTP only
- Business logic = UseCase
- Error = Exception
- Export = Application Service
