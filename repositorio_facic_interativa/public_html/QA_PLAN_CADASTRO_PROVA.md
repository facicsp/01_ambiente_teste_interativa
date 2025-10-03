# QA Plan - Cadastro de Prova Fix

## Executive Summary
**Issue:** Form submission fails - JavaScript receives malformed JSON response
**Root Cause:** `conexao.php` injects `<script>` tag before JSON output
**Status:** Fix developed, pending deployment and validation
**Priority:** HIGH - Critical user functionality blocked

---

## 1. Review Requirements

### Acceptance Criteria
- [ ] User can create a new prova with título
- [ ] User can add questões (objetivas and discursivas)
- [ ] User can add alternativas to questões objetivas
- [ ] User can mark correct alternativa
- [ ] System saves prova to database
- [ ] System saves questões to database
- [ ] System saves alternativas to database
- [ ] User receives success feedback
- [ ] User can add multiple questões to same prova

### Impacted Domains
- **Frontend:** Vue.js form (`cadastroProva2.php`, `criarProva.js`)
- **API:** PHP endpoint (`gravarProva2.php`)
- **Database:** Tables `prova`, `questao2`, `alternativa`
- **Session:** PHP session management for `idProva`

---

## 2. Test Planning

### Scenarios

#### Happy Path
1. **New Prova - Single Objetiva Question**
   - Fill título: "Teste QA"
   - Fill descrição: "Questão teste"
   - Add 2 alternativas
   - Mark one as correct
   - Submit
   - **Expected:** Success message, question saved, idProva stored in session

2. **Existing Prova - Add Second Question**
   - Continue from previous test
   - Add another question
   - Submit
   - **Expected:** Same idProva, new idQuestao

3. **Discursiva Question**
   - Toggle to discursiva
   - Fill only descrição
   - Submit
   - **Expected:** No alternativas required, saves successfully

4. **Multiple Alternativas (>2)**
   - Add up to 8 alternativas
   - Mark one as correct
   - Submit
   - **Expected:** All alternativas saved correctly

#### Edge Cases
1. **Empty Fields Validation**
   - Leave título empty → Should show error
   - Leave descrição empty → Should show error
   - No alternativa marked → Should show error

2. **File Upload**
   - Attach PDF file
   - Submit
   - **Expected:** File saved to uploads/anexos/

3. **Session Persistence**
   - Save question
   - Refresh page
   - **Expected:** idProva persists in session

#### Failure Modes
1. **Database Connection Lost**
   - Simulate DB disconnect
   - **Expected:** Error message, no partial save

2. **Invalid Session**
   - Clear session
   - Try to submit
   - **Expected:** "Access denied" error

3. **SQL Injection Attempt**
   - Input: `'; DROP TABLE prova; --`
   - **Expected:** Sanitized, no SQL executed

---

## 3. Environment Setup

### Prerequisites
```bash
# Dependencies (already installed)
- PHP 5.x/7.x
- MySQL
- Apache/Nginx
- Vue.js 2.5.21 (via CDN)
- Axios 0.18.0 (via CDN)

# Database Tables
- prova (idProva, titulo, idProfessor)
- questao2 (idQuestao, descricao, tipo, peso, idProva, anexo)
- alternativa (idAlternativa, texto, correta, idQuestao)
```

### Configuration
```php
// conexao.php
- Database: interativa.vpshost0360.mysql.dbaas.com.br
- User: interativa
- DB: interativa
```

### Test Data
```sql
-- Test user (already exists)
ID: 195
Email: adamfuzy@gmail.com
Tipo: professor
```

---

## 4. Execute Tests

### Automated Tests (TODO)

**File:** `tests/cadastro-prova.spec.js` (to be created)

```javascript
// Playwright test suite
describe('Cadastro Prova', () => {
  test('should create new prova with objetiva question', async ({ page }) => {
    await page.goto('/cadastroProva2.php');
    await page.fill('[placeholder="Digite um título"]', 'Teste Automatizado');
    await page.fill('#descricao', 'Questão de teste');
    await page.fill('.alternativa:nth-child(1) textarea', 'Alternativa A');
    await page.fill('.alternativa:nth-child(2) textarea', 'Alternativa B');
    await page.click('.alternativa:nth-child(1) .correta');
    await page.click('.salvar.botao.sucesso');

    await expect(page.locator('.sucesso')).toContainText('Questão salva');
  });
});
```

### Manual Testing Checklist

#### Pre-deployment Verification (Local)
- [x] Verify `criarProva.js` has `X-Requested-With` header
- [x] Verify `gravarProva2.php` handles `$_POST` correctly
- [x] Test locally if possible

#### Post-deployment Verification (Server)
- [ ] Upload `js/criarProva.js` to server
- [ ] Clear browser cache (Ctrl+Shift+Del)
- [ ] Hard refresh page (Ctrl+F5)
- [ ] Open DevTools Console (F12)
- [ ] Test: Create new prova with 1 question
- [ ] Verify: Console shows no errors
- [ ] Verify: Network tab shows JSON response (no `<script>`)
- [ ] Verify: Success message appears
- [ ] Verify: Database has new records
- [ ] Test: Add second question to same prova
- [ ] Verify: Same idProva used
- [ ] Test: Create discursiva question
- [ ] Test: Upload file attachment
- [ ] Test: All validation errors work

#### Database Verification
```sql
-- Check last inserted prova
SELECT * FROM prova ORDER BY idProva DESC LIMIT 1;

-- Check questões for that prova
SELECT * FROM questao2 WHERE idProva = [last_id] ORDER BY idQuestao DESC;

-- Check alternativas for last questão
SELECT * FROM alternativa WHERE idQuestao = [last_questao_id];
```

---

## 5. Report & Iterate

### Known Issues

#### Issue #1: `<script>` tag in JSON response
**Status:** FIXED (pending deployment)
**Files Changed:**
- `js/criarProva.js` (line 120)
- `js/criarProva_LOG.js` (line 135)

**Change:**
```javascript
headers: {
    "Content-Type": "multipart/form-data",
    "X-Requested-With": "XMLHttpRequest"  // ← Added this
}
```

**Why it works:**
- `conexao.php` checks for `X-Requested-With` header
- If present, skips `<script>` injection
- Returns pure JSON

**Verification:**
```bash
# Check response headers in DevTools
X-Requested-With: XMLHttpRequest

# Check response body (should be pure JSON, no <script>)
{"success":true,"idProva":"18111","idQuestao":"2327096",...}
```

### Deployment Instructions

**CRITICAL: Files to upload**
```
✅ MUST UPLOAD:
/public_html/js/criarProva.js

🔧 OPTIONAL (for debugging):
/public_html/js/criarProva_LOG.js
/public_html/gravarProva2_LOG.php
/public_html/cadastroProva2_LOG.php

⚠️ DO NOT UPLOAD:
/public_html/conexao.php (no changes needed)
/public_html/gravarProva2.php (no changes needed)
/public_html/cadastroProva2.php (no changes needed)
```

**Upload Steps:**
1. Connect to FTP: `ftp.interativa1.educacao.ws`
2. Navigate to: `/public_html/js/`
3. **BACKUP FIRST:** Download existing `criarProva.js` to `criarProva.js.backup`
4. Upload new `criarProva.js`
5. Verify upload timestamp matches current time
6. Test immediately

**Rollback Plan:**
If issues occur:
1. Upload `criarProva.js.backup` back to `criarProva.js`
2. System returns to previous state
3. Investigate further

### Post-Deployment Testing

**Immediate Tests (5 min):**
1. Open http://www.facicinterativa.com.br/ambiente_QA/cadastroProva2.php
2. Open DevTools Console (F12)
3. Fill form with test data
4. Click "SALVAR QUESTÃO"
5. **Expected:** "✔ Questão salva com sucesso!"
6. **Verify in console:** No errors, response is pure JSON

**Full Regression (15 min):**
1. Test all scenarios from "Test Planning" section
2. Verify database records
3. Test in different browsers (Chrome, Firefox, Edge)
4. Test on mobile (responsive)

### Monitoring

**For 24 hours after deployment:**
- Monitor error logs: `tail -f /var/log/apache2/error.log`
- Check database for orphaned records
- Monitor user reports
- Check session storage for leaks

### Outstanding Risks

1. **Cache Issues**
   - Risk: Users may still use cached old JavaScript
   - Mitigation: Version the file (`criarProva.js?v=2`) or send cache-busting headers

2. **Browser Compatibility**
   - Risk: Older browsers may not support axios/Vue properly
   - Mitigation: Already using stable versions (Vue 2.5.21, axios 0.18.0)

3. **Concurrent Edits**
   - Risk: Multiple professors editing same prova
   - Mitigation: Each professor has own session, separate idProva

4. **Database Load**
   - Risk: High traffic during peak hours
   - Mitigation: Queries are simple INSERTs, properly indexed

---

## 6. Documentation Updates

### Files to Update After Fix
- [ ] README.md - Add troubleshooting section
- [ ] CHANGELOG.md - Document this bug fix
- [ ] docs/api/gravar-prova.md - Document expected headers
- [ ] This QA plan - Mark as RESOLVED

---

## Appendix

### Debug Commands

```bash
# Check if file was uploaded
ls -lh /path/to/public_html/js/criarProva.js

# View last 50 lines of Apache error log
tail -50 /var/log/apache2/error.log

# Check MySQL for last inserted records
mysql -u interativa -p interativa -e "SELECT * FROM prova ORDER BY idProva DESC LIMIT 5;"

# Clear PHP sessions (if needed)
rm -rf /tmp/sess_*
```

### Contact Info

**Developer:** Claude AI Agent
**QA Lead:** [Your Name]
**Date:** 2025-10-01
**Version:** 1.0

---

## Sign-off

- [ ] Developer: Fix implemented and tested locally
- [ ] QA: Manual testing passed
- [ ] DevOps: Deployed to production
- [ ] Product: Acceptance criteria met
