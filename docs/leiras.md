# Triviaverse-web Dokumentáció

## 1. Bevezetés

### 1.1 Téma ismertetése
A **Triviaverse-web** egy interaktív, Vue.js alapú kvízplatform, amely lehetővé teszi a felhasználók számára, hogy különböző témákban kvízeket játsszanak, saját kvízeket hozzanak létre, és követhetik statisztikáikat. Az alkalmazás célja szórakoztatás és tanulási lehetőségek biztosítása.

### 1.2 Témaválasztás indoklása
A kvízjátékok népszerűségük miatt kiválóak oktatási és szórakoztató célokra egyaránt. A Triviaverse-web célja, hogy egyedi élményt nyújtson felhasználóinak, például személyre szabott kvízek létrehozása és megosztása által.

### 1.3 Szakmai célkitűzés
A projekt célja egy könnyen kezelhető, modern webes alkalmazás létrehozása, amely lehetőséget ad a felhasználóknak arra, hogy tudásukat játékos módon bővítsék, és barátaikkal versenyezzenek.

---

## 2. Fejlesztői dokumentáció

### 2.1 Fejlesztőkörnyezet ismertetése
- **Hardver**:
  - Processzor: Intel i5.
  - RAM: 8GB.
  - Háttértár: SSD.
- **Szoftverek**:
  - **Frontend**: Vue.js 3.
  - **Backend**: PHP 8.1.
  - **Adatbázis**: MySQL 8.0.
  - **Verziókezelés**: GitHub.
- **Fejlesztési URL**: [Triviaverse-web GitHub](https://github.com/Triviaverse/Triviaverse-web).

### 2.2 Adatszerkezet ismertetése
- **Táblák részletes leírása**:

#### 1. **users** tábla
- **Leírás**: A felhasználók adatait tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `name`: Felhasználó neve (varchar, max. 255 karakter).
  - `email`: Felhasználó e-mail címe (varchar, max. 255 karakter, egyedi).
  - `email_verified_at`: Az e-mail cím megerősítésének időpontja (timestamp).
  - `role`: Felhasználói szerepkör (enum: `admin`, `teacher`, `student`).
  - `password`: Jelszó (varchar, max. 255 karakter).
  - `remember_token`: Token a bejelentkezéshez (varchar, max. 100 karakter).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 2. **quizzes** tábla
- **Leírás**: A kvízek metaadatait tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `title`: Kvíz címe (varchar, max. 255 karakter).
  - `created_by`: A kvízt létrehozó felhasználó azonosítója (bigint, FOREIGN KEY a `users` táblára).
  - `is_active`: Aktív állapot (tinyint, alapértelmezett: 1).
  - `time_limit`: Időkorlát másodpercben (int).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 3. **questions** tábla
- **Leírás**: A kvízkérdéseket tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `quiz_id`: A kérdéshez tartozó kvíz azonosítója (bigint, FOREIGN KEY a `quizzes` táblára).
  - `question_text`: A kérdés szövege (text).
  - `type`: Kérdés típusa (enum: `multiple_choice`, `single_choice`, `text`).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 4. **answers** tábla
- **Leírás**: A kérdésekhez tartozó válaszokat tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `question_id`: A válaszhoz tartozó kérdés azonosítója (bigint, FOREIGN KEY a `questions` táblára).
  - `answer_text`: A válasz szövege (text).
  - `is_correct`: Helyes válasz-e (tinyint, alapértelmezett: 0).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 5. **quiz_attempts** tábla
- **Leírás**: A kvízpróbálkozásokat tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `quiz_id`: A próbálkozáshoz tartozó kvíz azonosítója (bigint, FOREIGN KEY a `quizzes` táblára).
  - `user_id`: A próbálkozó felhasználó azonosítója (bigint, FOREIGN KEY a `users` táblára).
  - `is_completed`: Befejezett állapot (tinyint, alapértelmezett: 0).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 6. **quiz_results** tábla
- **Leírás**: A kvízpróbálkozások eredményeit tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `quiz_attempt_id`: A próbálkozás azonosítója (bigint, FOREIGN KEY a `quiz_attempts` táblára).
  - `score_percentage`: Eredmény százalékban (int).
  - `is_overridden`: Felülírt eredmény-e (tinyint, alapértelmezett: 0).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 7. **quiz_permissions** tábla
- **Leírás**: A kvízekhez való hozzáférési jogosultságokat tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (bigint, AUTO_INCREMENT).
  - `quiz_id`: A kvíz azonosítója (bigint, FOREIGN KEY a `quizzes` táblára).
  - `user_id`: A felhasználó azonosítója (bigint, FOREIGN KEY a `users` táblára).
  - `is_allowed`: Jogosultság állapota (tinyint, alapértelmezett: 0).
  - `created_at`: Létrehozás időpontja (timestamp).
  - `updated_at`: Módosítás időpontja (timestamp).

#### 8. **sessions** tábla
- **Leírás**: A felhasználói munkameneteket tárolja.
- **Oszlopok**:
  - `id`: Egyedi azonosító (varchar, max. 255 karakter).
  - `user_id`: A felhasználó azonosítója (bigint, FOREIGN KEY a `users` táblára).
  - `ip_address`: IP-cím (varchar, max. 45 karakter).
  - `user_agent`: Böngésző adatai (text).
  - `payload`: Munkamenet adatai (longtext).
  - `last_activity`: Utolsó aktivitás időpontja (int).

### 2.3 Tipikus algoritmusok
- **Pontszámítás algoritmusa**:
  - Összeadja a helyes válaszokért járó pontokat és meghatározza a százalékos eredményt.
- **Kérdések véletlenszerű sorrendbe állítása**:
  - Kérdéseket keverő funkció, amely a játékosok számára egyedi élményt biztosít.
- **Felhasználói regisztráció ellenőrzése**:
  - Biztosítja, hogy az e-mail cím érvényes és nem duplikált.
- **Statisztikák mentése**:
  - A felhasználók eredményeit elmenti a statisztikai táblába.

### 2.4 Tesztdokumentáció
- **Tesztesetek**:
  1. Hibás e-mail cím regisztrációnál: Ellenőrizve, hogy a rendszer megfelelő hibaüzenetet ad.
  2. Üres űrlapok beküldése: A rendszer figyelmeztető üzenetet jelenít meg.
  3. Nagyméretű adathalmaz kezelése: A rendszer stabilitása tesztelve nagy mennyiségű adat esetén.
  4. Böngészők közötti kompatibilitás (Chrome, Firefox): Az alkalmazás minden funkciója megfelelően működik.
  5. Hibás bejelentkezési adatok kezelése: A rendszer hibaüzenetet jelenít meg.
  6. Egyidejű adathozzáférés és mentés: Ellenőrizve, hogy nincs adatvesztés.
- **Tesztelési környezet**:
  - Böngészők: Chrome, Firefox, Edge.
  - Eszközök: Asztali gép, mobiltelefon.
- **Tesztelői hozzáférés**:
  - Felhasználónév: `testuser`
  - Jelszó: `testpass123`

- **Teszteredmények**:
  - A hibás e-mail címekre a rendszer "Érvénytelen e-mail cím" üzenetet jelenít meg.
  - Üres űrlapok esetén a mezők piros keretet kapnak, és figyelmeztető üzenet jelenik meg.
  - Nagyméretű adathalmaz esetén az alkalmazás válaszideje nem haladta meg az 1 másodpercet.
  - Böngészők közötti kompatibilitás tesztje során nem találtunk eltéréseket a funkciók működésében.

### 2.5 Fejlesztési lehetőségek
1. **Többjátékos mód**: Valós idejű kvízjáték több felhasználó között.
2. **Mobilalkalmazás verzió**: Android és iOS platformokra optimalizált változat.

---

## 3. Felhasználói dokumentáció

### 3.1 A program célja és funkciói
- **Cél**: Szórakozás és tanulás kvízek segítségével.
- **Funkciók**:
  - Kvízek játszása.
  - Egyedi kvízek létrehozása és megosztása.
  - Statisztikák megtekintése.

### 3.2 Hardver- és szoftverkövetelmények
- **Hardver**:
  - Asztali gép vagy mobil eszköz.
- **Szoftver**:
  - Modern böngésző (Chrome, Firefox, Edge).

### 3.3 Telepítés és indítás
- A program online érhető el a következő címen: [Triviaverse-web](https://hueserver.hu).

### 3.4 Funkciók részletes bemutatása
- **Kvíz indítása**:
  - A felhasználók nyilvános kvízek közül választhatnak.
- **Statisztikák megtekintése**:
  - Az eredmények grafikonos megjelenítése.
- **Saját kvízek készítése**:
  - Kérdések és válaszok megadása egy szerkesztőfelületen.

### 3.5 Hibakezelés
- Hibás belépés esetén hibaüzenet jelenik meg.
- Üres mezők figyelmeztetése űrlapok kitöltésekor.

### 3.6 Adminisztrátori funkciók
- Felhasználók és kvízek kezelése.
- Speciális jogosultságok, például globális kvízek létrehozása.

### 3.7 Kapcsolattartási információk
- **E-mail**: support@triviaverse.com.

---

## 4. Összefoglalás

### 4.1 Értékelés
A Triviaverse-web elérte célját, hogy felhasználóbarát platformot nyújtson tanuláshoz és szórakozáshoz. A jövőben további funkciókkal bővíthető, például többjátékos mód és mobilalkalmazás formájában.

### 4.2 Köszönetnyilvánítás
Köszönet a mentornak és a csapat tagjainak a támogatásért.

---

## 5. Irodalomjegyzék
- [Vue.js hivatalos dokumentáció](https://vuejs.org)
- [PHP.net](https://php.net)

---

## Formai követelmények
- Másfeles sortáv, 12-es betűméret.
- Sorkizárt bekezdések.
- Automatikus tartalomjegyzék generálás.
