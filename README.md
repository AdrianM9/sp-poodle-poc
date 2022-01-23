## Mihai Iamandei, Teodor-Adrian Mirea (SAS - 1st year)
### SP - Assignment, 2021

# Poodle Attack - PoC
This project presents a very popular attack running on the SSL/TLS protocol, the POODLE Attack (Padding Oracle On Downgraded Legacy Encryption).

The code represents a proof-of-concept (PoC) that demonstrates the practicability of the attack. It is based on the implementation made by RootDev4 described in [his repository](https://github.com/RootDev4/poodle-PoC). The environment presented there uses three virtual machines: a vulnerable server and a virtual machine for each of the victim and the attacker. There are some changes that have been made to the original code which are described in the following statements. Firstly, the websites used by the victim and attacker have been modified in order to achieve a scenario closer to reality, but also to automate the process of the attack. Another modification regarding this latter aspect consists of changing the exploit script in such a way that it goes through _ping_, _downgrade_, _search_ and _active_ stages automatically and in sync with the JavaScript functions that are being executed on the victim's browser. We also modified the setup of the vulnerable server, making it use an 8 bytes session cookie. This was done only for time related aspects.

The files from each folder should be copied into the specific virtual machine. The proper steps to follow are presented in the article, in the **Proof-of-Concept description and implementation** section, at the fourth subsection **Running the attack**.
