# Εγκατάσταση

1) Ανεβάζουμε τον φάκελο pointer στον φάκελο /modules/registrars/

2) Ανεβάζουμε τον φάκελο addonpointer στον φάκελο /modules/addons/

3) Ανεβάζουμε το αρχείο whois.json στον φάκελο /resources/domains/

4) Ανεβάζουμε το αρχείο additionalfields.php στον φάκελο /resources/domains/

5) Μέσα από το whmcs ενεργοποιούμε το module:
Πηγαίνουμε Setup -> Products/Services -> Domain Registrars. (To module ονομάζεται Pointer)
Εισάγετε το όνομα χρήστη και τον κωδικό που σας έδωσε η Pointer (δεν είναι ίδια με τα στοιχεία σύνδεσης του panel σας).
Βεβαιωθείτε ότι το checkbox "TestΜode" ΔΕΝ είναι τσεκαρισμένο.

6) Μέσα από το whmcs ενεργοποιούμε το addon:
Πηγαίνουμε Setup -> Addon modules. (To addon ονομάζεται Addon Pointer)

7) Στο Setup -> Products/Services -> Domain pricing ορίζουμε σαν "Auto Registration" το module "pointer" για τις καταλήξεις που θέλουμε.

# Αυτόματη εισαγωγή domain και τιμών

1) Πάμε στο Utilities -> Registrar TLD Sync

2) Κάνουμε κλικ στο λογότυπο της Pointer

3) Επιλέγουμε τους κανόνες που θα διαμορφώσουν τις τιμές (ποσοστό, στρογγυλοποίηση κτλ)

4) Τσεκάρουμε το "Automatic Registration" για να γίνεται αυτόματη κατοχύρωση

4) Επιλέγουμε τις καταλήξεις που θέλουμε να εισάγουμε (προσοχή, είναι κατηγοριοποιημένα σε tabs).

5) Πατάμε το κουμπί "Import tlds"

