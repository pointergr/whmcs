1) Ανεβάζουμε τον φάκελο pointer στον φάκελο /modules/registrars/

2) Ανεβάζουμε τον φάκελο addonpointer στον φάκελο /modules/addons/

3) Έχουμε δύο περιπτώσεις ανάλογα με την έκδοση του whmcs: 

Για εκδόσεις από 7.0 και άνω: Ανεβάζουμε το αρχείο whois.json στον φάκελο /resources/domains/

Για εκδόσεις πριν την 7.0: Επεξεργαζόμαστε το αρχείο /includes/whoisservers.php και ελέγχουμε αν υπάρχουν γραμμές που ξεκινούν με τις καταλήξεις: .gr, .com.gr, .net.gr, .org.gr, .edu.gr, .gov.gr
Αν υπάρχουν ήδη, αντικαθιστούμε τις γραμμές με τις παρακάτω, αλλιώς απλά προσθέτουμε τις πατακάτω στο τέλος του αρχείου.
```
.gr|https://www.pointer.gr/el/domain-names/publicwhois/|HTTPREQUEST-Domain does not exist.
.com.gr|https://www.pointer.gr/el/domain-names/publicwhois/|HTTPREQUEST-Domain does not exist
.net.gr|https://www.pointer.gr/el/domain-names/publicwhois/|HTTPREQUEST-Domain does not exist
.org.gr|https://www.pointer.gr/el/domain-names/publicwhois/|HTTPREQUEST-Domain does not exist
.edu.gr|https://www.pointer.gr/el/domain-names/publicwhois/|HTTPREQUEST-Domain does not exist
.gov.gr|https://www.pointer.gr/el/domain-names/publicwhois/|HTTPREQUEST-Domain does not exist
```

4) Έχουμε δύο περιπτώσεις ανάλογα με την έκδοση του whmcs:

Για εκδόσεις από 7.0 και άνω: Ανεβάζουμε το αρχείο additionalfields.php στον φάκελο /resources/domains/

Για εκδόσεις πριν την 7.0: Προσθέτουμε στο τέλος του αρχείου includes/additionaldomainfields.php τον παρακάτω κώδικα: 
```php
if (file_exists(dirname(__FILE__) . "/../modules/registrars/pointer/additionaldomainfields.php")) {
    include dirname(__FILE__) . "/../modules/registrars/pointer/additionaldomainfields.php";
}
```

5) Μέσα από το whmcs ενεργοποιούμε το module:
Πηγαίνουμε Setup -> Products/Services -> Domain Registrars. (To module ονομάζεται Pointer)
Εισάγετε το όνομα χρήστη και τον κωδικό που σας έδωσε η Pointer (δεν είναι ίδια με τα στοιχεία σύνδεσης του panel σας).
Βεβαιωθείτε ότι το checkbox "TestΜode" ΔΕΝ είναι τσεκαρισμένο.

6) Μέσα από το whmcs ενεργοποιούμε το addon:
Πηγαίνουμε Setup -> Addon modules. (To addon ονομάζεται Addon Pointer)

7) Στο Setup -> Products/Services -> Domain pricing ορίζουμε σαν "Auto Registration" το module "pointer" για τις καταλήξεις που θέλουμε.
