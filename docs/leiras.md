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
- **Táblák**:
  - `users`: Felhasználói adatok tárolása.
  - `quizzes`: Kvízek metaadatai.
  - `questions`: Kvízkérdések tárolása.
  - `answers`: A kérdésekhez tartozó válaszok.
- **Kapcsolatok**:
  - A `users` és a `quizzes` táblák között kapcsolat van, ahol a kvízeket a felhasználók hozzák létre.
- **Adatbázis ábra**: Az ER-diagram bemutatja a táblák közötti kapcsolatokat.

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
  1. Hibás e-mail cím regisztrációkor.
  2. Üres űrlapok beküldése.
  3. Nagyméretű adathalmaz kezelése.
  4. Böngészők közötti kompatibilitás (Chrome, Firefox).
  5. Hibás bejelentkezési adatok kezelése.
  6. Egyidejű adathozzáférés és mentés.
- **Tesztelési környezet**:
  - Böngészők: Chrome, Firefox, Edge.
  - Eszközök: Asztali gép, mobiltelefon.
- **Tesztelői hozzáférés**:
  - Felhasználónév: `testuser`
  - Jelszó: `testpass123`

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
