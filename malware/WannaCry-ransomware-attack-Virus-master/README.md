# WannaCry ransomware Attack Virus
WannaCry ransomware attack { Virus }

The WannaCry ransomware attack was a May 2017 worldwide cyberattack by the WannaCry ransomware cryptoworm, which targeted computers running the Microsoft Windows operating system by encrypting data and demanding ransom payments in the Bitcoin cryptocurrency

https://en.wikipedia.org/wiki/WannaCry_ransomware_attack

The WannaCry ransomware attack was a May 2017 worldwide cyberattack by the WannaCry ransomware cryptoworm, which targeted computers running the Microsoft Windows operating system by encrypting data and demanding ransom payments in the Bitcoin cryptocurrency. It propagated through EternalBlue, an exploit developed by the United States National Security Agency (NSA) for older Windows systems. EternalBlue was stolen and leaked by a group called The Shadow Brokers a few months prior to the attack. While Microsoft had released patches previously to close the exploit, much of WannaCry's spread was from organizations that had not applied these, or were using older Windows systems that were past their end-of-life.

The attack was halted within a few days of its discovery due to emergency patches released by Microsoft and the discovery of a kill switch that prevented infected computers from spreading WannaCry further. The attack was estimated to have affected more than 200,000 computers across 150 countries, with total damages ranging from hundreds of millions to billions of dollars. Security experts believed from preliminary evaluation of the worm that the attack originated from North Korea or agencies working for the country.

In December 2017, the United States, United Kingdom and Australia formally asserted that North Korea was behind the attack.[5]

A new variant of WannaCry forced Taiwan Semiconductor Manufacturing Company (TSMC) to temporarily shut down several of its chip-fabrication factories in August 2018. The virus spread to 10,000 machines in TSMC's most advanced facilities.[6]






WannaCry ransomware attack
From Wikipedia, the free encyclopedia
Jump to navigationJump to search
‹ The template Infobox event is being considered for merging. ›
WannaCry
Wana Decrypt0r screenshot.png
Screenshot of the ransom note left on an infected system
Date	12 May 2017 – 15 May 2017
(initial outbreak)[1]
Duration	4 days
Location	Worldwide
Also known as	Transformations:
Wanna → Wana
Cryptor → Crypt0r
Cryptor → Decryptor
Cryptor → Crypt → Cry
Addition of "2.0"
Short names:
Wanna → WN → W
Cry → CRY
Type	Cyberattack
Theme	Ransomware encrypting files with $300 – $600 USD demand (via bitcoin)
Cause	
WannaCry worm
Outcome	Over 200,000 victims and more than 300,000 computers infected[2][3][4]
Arrests	None
Suspects	Lazarus Group
Accused	Two North Koreans Indicted
Convictions	None
WannaCry
Subtype	Ransomware
Point of origin	Pyongyang, North Korea
Author(s)	Lazarus Group
The WannaCry ransomware attack was a May 2017 worldwide cyberattack by the WannaCry ransomware cryptoworm, which targeted computers running the Microsoft Windows operating system by encrypting data and demanding ransom payments in the Bitcoin cryptocurrency. It propagated through EternalBlue, an exploit developed by the United States National Security Agency (NSA) for older Windows systems. EternalBlue was stolen and leaked by a group called The Shadow Brokers a few months prior to the attack. While Microsoft had released patches previously to close the exploit, much of WannaCry's spread was from organizations that had not applied these, or were using older Windows systems that were past their end-of-life.

The attack was halted within a few days of its discovery due to emergency patches released by Microsoft and the discovery of a kill switch that prevented infected computers from spreading WannaCry further. The attack was estimated to have affected more than 200,000 computers across 150 countries, with total damages ranging from hundreds of millions to billions of dollars. Security experts believed from preliminary evaluation of the worm that the attack originated from North Korea or agencies working for the country.

In December 2017, the United States, United Kingdom and Australia formally asserted that North Korea was behind the attack.[5]

A new variant of WannaCry forced Taiwan Semiconductor Manufacturing Company (TSMC) to temporarily shut down several of its chip-fabrication factories in August 2018. The virus spread to 10,000 machines in TSMC's most advanced facilities.[6]


Contents
1	Description
2	Attack
2.1	Defensive response
3	Attribution
4	Impact
4.1	Affected organizations
5	Reactions
5.1	United Kingdom
6	2018 email scam
7	See also
8	References
9	External links
Description
WannaCry is a ransomware cryptoworm, which targeted computers running the Microsoft Windows operating system by encrypting data and demanding ransom payments in the Bitcoin cryptocurrency. The worm is also known as WannaCrypt,[7] Wana Decrypt0r 2.0,[8] WanaCrypt0r 2.0,[9] and Wanna Decryptor.[10] It is considered a network worm because it also includes a "transport" mechanism to automatically spread itself. This transport code scans for vulnerable systems, then uses the EternalBlue exploit to gain access, and the DoublePulsar tool to install and execute a copy of itself.[11] WannaCry versions 0, 1, and 2 were created using Microsoft Visual C++ 6.0.[12]

EternalBlue is an exploit of Windows' Server Message Block (SMB) protocol released by The Shadow Brokers. Much of the attention and comment around the event was occasioned by the fact that the U.S. National Security Agency (NSA) (from whom the exploit was likely stolen) had already discovered the vulnerability, but used it to create an exploit for its own offensive work, rather than report it to Microsoft.[13][14] Microsoft eventually discovered the vulnerability, and on Tuesday, 14 March 2017, they issued security bulletin MS17-010, which detailed the flaw and announced that patches had been released for all Windows versions that were currently supported at that time, these being Windows Vista, Windows 7, Windows 8.1, Windows 10, Windows Server 2008, Windows Server 2008 R2, Windows Server 2012, and Windows Server 2016.[15]

DoublePulsar is a backdoor tool, also released by The Shadow Brokers on 14 April 2017. Starting from 21 April 2017, security researchers reported that there were tens of thousands of computers with the DoublePulsar backdoor installed.[16] By 25 April, reports estimated that the number of infected computers could be up to several hundred thousand, with numbers increasing every day.[17][18] The WannaCry code can take advantage of any existing DoublePulsar infection, or installs it itself.[11][19][20] On 9 May 2017, private cybersecurity company RiskSense released code on the website github.com with the stated purpose of allowing legal “white hat” penetration testers to test the CVE-2017-0144 exploit on unpatched systems.

When executed, the WannaCry malware first checks the "kill switch" domain name; if it is not found, then the ransomware encrypts the computer's data,[21][22][23] then attempts to exploit the SMB vulnerability to spread out to random computers on the Internet,[24] and "laterally" to computers on the same network.[25] As with other modern ransomware, the payload displays a message informing the user that files have been encrypted, and demands a payment of around US$300 in bitcoin within three days, or US$600 within seven days.[22][26] Three hardcoded bitcoin addresses, or "wallets", are used to receive the payments of victims. As with all such wallets, their transactions and balances are publicly accessible even though the cryptocurrency wallet owners remain unknown.[27]

Several organizations released detailed technical writeups of the malware, including a senior security analyst at RiskSense,[28][29] Microsoft,[30] Cisco,[11] Malwarebytes,[24] Symantec and McAfee.[25]

Attack
The attack began on Friday, 12 May 2017,[31][32] with evidence pointing to an initial infection in Asia at 07:44 UTC.[31][33] The initial infection was likely through an exposed vulnerable SMB port,[34] rather than email phishing as initially assumed.[31] Within a day the code was reported to have infected more than 230,000 computers in over 150 countries.[35][36]

Organizations that had not installed Microsoft's security update from April 2017 were affected by the attack.[37] Those still running unsupported versions of Microsoft Windows, such as Windows XP and Windows Server 2003[38][39] were at particularly high risk because no security patches had been released since April 2014 for Windows XP (with the exception of one emergency patch released in May 2014) and July 2015 for Windows Server 2003.[7] A Kaspersky Lab study reported however, that less than 0.1 percent of the affected computers were running Windows XP, and that 98 percent of the affected computers were running Windows 7.[7][40] In a controlled testing environment, the cybersecurity firm Kryptos Logic found that they were unable to infect a Windows XP system with WannaCry using just the exploits, as the payload failed to load, or caused the operating system to crash rather than actually execute and encrypt files. However, when executed manually, WannaCry could still operate on Windows XP.[41][42][43]

Defensive response
Experts quickly advised affected users against paying the ransom due to no reports of people getting their data back after payment and as high revenues would encourage more of such campaigns.[44][45][46] As of 14 June 2017, after the attack had subsided, a total of 327 payments totaling US$130,634.77 (51.62396539 XBT) had been transferred.[47]

The day after the initial attack in May, Microsoft released out-of-band security updates for end of life products Windows XP, Windows Server 2003 and Windows 8; these patches had been created in February of that year following a tip off about the vulnerability in January of that year.[48][39] Organizations were advised to patch Windows and plug the vulnerability in order to protect themselves from the cyber attack.[49] The head of Microsoft's Cyber Defense Operations Center, Adrienne Hall, said that “Due to the elevated risk for destructive cyber-attacks at this time, we made the decision to take this action because applying these updates provides further protection against potential attacks with characteristics similar to WannaCrypt [alternative name to WannaCry]”.[50][51]

Researcher Marcus Hutchins[52][53] discovered the kill switch domain hardcoded in the malware.[54][55][56] Registering a domain name for a DNS sinkhole stopped the attack spreading as a worm, because the ransomware only encrypted the computer's files if it was unable to connect to that domain, which all computers infected with WannaCry before the website's registration had been unable to do. While this did not help already infected systems, it severely slowed the spread of the initial infection and gave time for defensive measures to be deployed worldwide, particularly in North America and Asia, which had not been attacked to the same extent as elsewhere.[57][58][59][60][61] On 14 May, a first variant of WannaCry appeared with a new and second[62] kill-switch registered by Matt Suiche on the same day. This was followed by a second variant with the third and last kill-switch on 15 May, which was registered by Check Point threat intelligence analysts.[63][64] A few days later, a new version of WannaCry was detected that lacked the kill switch altogether.[65][66][67][68]

On 19 May, it was reported that hackers were trying to use a Mirai botnet variant to effect a distributed attack on WannaCry's kill-switch domain with the intention of knocking it offline.[69] On 22 May, Hutchins protected the domain by switching to a cached version of the site, capable of dealing with much higher traffic loads than the live site.[70]

Separately, researchers from University College London and Boston University reported that their PayBreak system could defeat WannaCry and several other families of ransomware by recovering the keys used to encrypt the user's data.[71][72]

It was discovered that Windows encryption APIs used by WannaCry may not completely clear the prime numbers used to generate the payload's private keys from the memory, making it potentially possible to retrieve the required key if they had not yet been overwritten or cleared from resident memory. The key is kept in the memory if the WannaCry process has not been killed and the computer has not been rebooted after being infected.[73] This behaviour was used by a French researcher to develop a tool known as WannaKey, which automates this process on Windows XP systems.[74][75][76] This approach was iterated upon by a second tool known as Wanakiwi, which was tested to work on Windows 7 and Server 2008 R2 as well.[77]

Within four days of the initial outbreak, new infections had slowed to a trickle due to these responses.[78]

Attribution
Linguistic analysis of the ransom notes indicated the authors were likely fluent in Chinese and proficient in English, as the versions of the notes in those languages were probably human-written while the rest seemed to be machine-translated.[79][80] According to an analysis by the FBI's Cyber Behavioral Analysis Center, the computer that created the ransomware language files had Hangul language fonts installed, as evidenced by the presence of the "\fcharset129" Rich Text Format tag.[12] Metadata in the language files also indicated that the computers that created the ransomware were set to UTC+09:00, used in Korea.[12]

A Google security researcher[81][82] initially posted a tweet[83] referencing code similarities between WannaCry and previous malware. Then cybersecurity companies[84] Kaspersky Lab and Symantec have both said the code has some similarities with that previously used by the Lazarus Group[85] (believed to have carried out the cyberattack on Sony Pictures in 2014 and a Bangladesh bank heist in 2016—and linked to North Korea).[85] This could also be either simple re-use of code by another group[86] or an attempt to shift blame—as in a cyber false flag operation;[85] but a leaked internal NSA memo is alleged to have also linked the creation of the worm to North Korea.[87] Brad Smith, the president of Microsoft, said he believed North Korea was the originator of the WannaCry attack,[88] and the UK's National Cyber Security Centre reached the same conclusion.[89]

On 18 December 2017, the United States Government formally announced that it publicly considers North Korea to be the main culprit behind the WannaCry attack.[90] President Trump's Homeland Security Advisor, Tom Bossert, wrote an op-ed in The Wall Street Journal about this charge, saying "We do not make this allegation lightly. It is based on evidence."[91] In a press conference the following day, Bossert said that the evidence indicates that Kim Jong-un had given the order to launch the malware attack.[92] Bossert said that Canada, New Zealand and Japan agree with the United States' assessment of the evidence that links the attack to North Korea,[93] while the United Kingdom's Foreign and Commonwealth Office says it also stands behind the United States' assertion.[94]

North Korea, however, denied being responsible for the cyberattack.[95][96]

On 6 September 2018, the US Department of Justice (DoJ) announced formal charges against Park Jin-hyok for involvement in the Sony Pictures hack of 2014. The DoJ contended that Park was a North Korean hacker working as part of a team of experts for the North Korean Reconnaissance General Bureau. The Department of Justice asserted this team also had been involved in the WannaCry attack, among other activities.[97][98]

Impact

Map of the countries initially affected[99]
The ransomware campaign was unprecedented in scale according to Europol,[35] which estimates that around 200,000 computers were infected across 150 countries. According to Kaspersky Lab, the four most affected countries were Russia, Ukraine, India and Taiwan.[100]

One of the largest agencies struck by the attack was the National Health Service hospitals in England and Scotland,[101][102] and up to 70,000 devices – including computers, MRI scanners, blood-storage refrigerators and theatre equipment – may have been affected.[103] On 12 May, some NHS services had to turn away non-critical emergencies, and some ambulances were diverted.[104][105] In 2016, thousands of computers in 42 separate NHS trusts in England were reported to be still running Windows XP.[38] In 2018 a report by Members of Parliament concluded that all 200 NHS hospitals or other organizations checked in the wake of the WannaCry attack still failed cyber security checks.[106][107] NHS hospitals in Wales and Northern Ireland were unaffected by the attack.[108][104]

Nissan Motor Manufacturing UK in Tyne and Wear, England, halted production after the ransomware infected some of their systems. Renault also stopped production at several sites in an attempt to stop the spread of the ransomware.[109][110] Spain's Telefónica, FedEx and Deutsche Bahn were hit, along with many other countries and companies worldwide.[111][112][113]

The attack's impact is said to be relatively low compared to other potential attacks of the same type and could have been much worse had Marcus Hutchins not discovered that a kill-switch had been built in by its creators[114][115] or if it had been specifically targeted on highly critical infrastructure, like nuclear power plants, dams or railway systems.[116][117]

According to cyber-risk-modeling firm Cyence, economic losses from the cyber attack could reach up to US$4 billion, with other groups estimating the losses to be in the hundreds of millions.[118]

Affected organizations
The following is an alphabetical list of organisations confirmed to have been affected:

Andhra Pradesh Police, India[119]
Aristotle University of Thessaloniki, Greece[120]
Automobile Dacia, Romania[121]
Boeing Commercial Airplanes[122]
Cambrian College, Canada[123]
Chinese public security bureau[124]
CJ CGV (a cinema chain)[125]
Dalian Maritime University[126]
Deutsche Bahn[127]
Dharmais Hospital, Indonesia[128]
Faculty Hospital, Nitra, Slovakia[129]
FedEx[130]
Garena Blade and Soul[131]
Guilin University of Aerospace Technology[126]
Guilin University of Electronic Technology[126]
Harapan Kita Hospital, Indonesia[128]
Hezhou University[126]
Hitachi[132]
Honda[133]
Instituto Nacional de Salud, Colombia[134]
Lakeridge Health[135]
LAKS, Netherlands [136]
LATAM Airlines Group[137]
MegaFon[138]
Ministry of Internal Affairs of the Russian Federation[139]
Ministry of Foreign Affairs (Romania)[140]
National Health Service (England)[141][104][108]
NHS Scotland[104][108]
Nissan Motor Manufacturing UK[141]
O2, Germany[142][143]
Petrobrás[144]
PetroChina[111][124]
Portugal Telecom[145]
Pulse FM[146]
Q-Park[147]
Renault[148]
Russian Railways[149]
Sandvik[128]
Justice Court of São Paulo[144]
Saudi Telecom Company[150]
Sberbank[151]
Shandong University[126]
State Governments of India
Government of Gujarat[152]
Government of Kerala[152]
Government of Maharashtra[153]
Government of West Bengal[152]
Suzhou Vehicle Administration[126]
Sun Yat-sen University, China[128]
Telefónica, Spain[154]
Telenor Hungary, Hungary[155]
Telkom (South Africa)[156]
Timrå Municipality, Sweden[157]
TSMC, Taiwan[158]
Universitas Jember, Indonesia[159]
University of Milano-Bicocca, Italy[160]
University of Montreal, Canada[161]
Vivo, Brazil[144]
Reactions
A number of experts highlighted the NSA's non-disclosure of the underlying vulnerability, and their loss of control over the EternalBlue attack tool that exploited it. Edward Snowden said that if the NSA had "privately disclosed the flaw used to attack hospitals when they found it, not when they lost it, the attack may not have happened".[162] British cybersecurity expert Graham Cluley also sees "some culpability on the part of the U.S. intelligence services". According to him and others "they could have done something ages ago to get this problem fixed, and they didn't do it". He also said that despite obvious uses for such tools to spy on people of interest, they have a duty to protect their countries' citizens.[163] Others have also commented that this attack shows that the practice of intelligence agencies to stockpile exploits for offensive purposes rather than disclosing them for defensive purposes may be problematic.[115] Microsoft president and chief legal officer Brad Smith wrote, "Repeatedly, exploits in the hands of governments have leaked into the public domain and caused widespread damage. An equivalent scenario with conventional weapons would be the U.S. military having some of its Tomahawk missiles stolen."[164][165][166] Russian President Vladimir Putin placed the responsibility of the attack on U.S. intelligence services, for having created EternalBlue.[151]

On 17 May 2017, United States bipartisan lawmakers introduced the PATCH Act[167] that aims to have exploits reviewed by an independent board to "balance the need to disclose vulnerabilities with other national security interests while increasing transparency and accountability to maintain public trust in the process".[168]

On 15 June 2017, the United States Congress was to hold a hearing on the attack.[169] Two subpanels of the House Science Committee were to hear the testimonies from various individuals working in the government and non-governmental sector about how the US can improve its protection mechanisms for its systems against similar attacks in the future.[169]

Marcus Hutchins, a cybersecurity researcher, working in loose collaboration with UK's National Cyber Security Centre,[170][171] researched the malware and discovered a "kill switch".[53] Later globally dispersed security researchers collaborated online to develop open source tools[172][173] that allow for decryption without payment under some circumstances.[174] Snowden states that when "NSA-enabled ransomware eats the Internet, help comes from researchers, not spy agencies" and asks why this is the case.[175][176][171]

Other experts also used the publicity around the attack as a chance to reiterate the value and importance of having good, regular and secure backups, good cybersecurity including isolating critical systems, using appropriate software, and having the latest security patches installed.[177] Adam Segal, director of the digital and cyberspace policy program at the Council on Foreign Relations, stated that "the patching and updating systems are broken, basically, in the private sector and in government agencies".[115] In addition, Segal said that governments' apparent inability to secure vulnerabilities "opens a lot of questions about backdoors and access to encryption that the government argues it needs from the private sector for security".[115] Arne Schönbohm, president of Germany's Federal Office for Information Security (BSI), stated that "the current attacks show how vulnerable our digital society is. It's a wake-up call for companies to finally take IT security [seriously]".[178]

United Kingdom
The effects of the attack also had political implications; in the United Kingdom, the impact on the National Health Service quickly became political, with claims that the effects were exacerbated by Government underfunding of the NHS; in particular, the NHS ceased its paid Custom Support arrangement to continue receiving support for unsupported Microsoft software used within the organization, including Windows XP.[179] Home Secretary Amber Rudd refused to say whether patient data had been backed up, and Shadow Health Secretary Jon Ashworth accused Health Secretary Jeremy Hunt of refusing to act on a critical note from Microsoft, the National Cyber Security Centre (NCSC) and the National Crime Agency that had been received two months previously.[180]

Others argued that hardware and software vendors often fail to account for future security flaws, selling systems that − due to their technical design and market incentives − eventually won't be able to properly receive and apply patches.[181]

The NHS denied that it was still using XP, claiming only 4.7% of devices within the organization ran Windows XP.[182][41] The cost of the attack to the NHS was estimated as £92 million in disruption to services and IT upgrades.[183]

After the attack, NHS Digital refused to finance the estimated £1 billion to meet the Cyber Essentials Plus standard, an information security certification organized by the UK NCSC, saying this would not constitute "value for money", and that it had invested over £60 million and planned "to spend a further £150 [million] over the next two years" to address key cyber security weaknesses.[184]

2018 email scam
In late June, hundreds of computer users reported being sent an email from someone (or multiple people), claiming to be the developers of WannaCry.[185] The email threatened to destroy the victims' data unless they sent 0.1 BTC to the Bitcoin address of the hackers. This has also happened in 2019.[citation needed]

See also
BlueKeep (security vulnerability)
Computer security § Medical systems
Comparison of computer viruses
Conficker
CryptoLocker
Cyber self-defense
Cyberweapon § Control and disarmament
International Multilateral Partnership Against Cyber Threats
Proactive cyber defence § Measures
Security engineering
Software versioning
SQL Slammer
Timeline of computer viruses and worms
Vault 7
Windows Update
2016 Dyn cyberattack
2017 Petya cyberattack
References
 "The WannaCry ransomware attack was temporarily halted. But it's not over yet".
 "Ransomware attack still looms in Australia as Government warns WannaCry threat not over". Australian Broadcasting Corporation. Retrieved 15 May 2017.
 Cameron, Dell. "Today's Massive Ransomware Attack Was Mostly Preventable; Here's How To Avoid It". Gizmodo. Retrieved 13 May 2017.
 "Shadow Brokers threaten to release Windows 10 hacking tools". The Express Tribune. 31 May 2017. Retrieved 31 May 2017.
 Thomas P. Bossert (18 December 2017). "It's Official: North Korea Is Behind WannaCry". The Wall Street Journal. Retrieved 19 December 2017.
 "TSMC Chip Maker Blames WannaCry Malware for Production Halt". The Hacker News. Retrieved 7 August 2018.
 MSRC Team. "Customer Guidance for WannaCrypt attacks". Microsoft. Retrieved 13 May 2017.
 Jakub Kroustek (12 May 2017). "Avast reports on WanaCrypt0r 2.0 ransomware that infected NHS and Telefonica". Avast Security News. Avast Software, Inc.
 Fox-Brewster, Thomas. "An NSA Cyber Weapon Might Be Behind A Massive Global Ransomware Outbreak". Forbes. Retrieved 12 May 2017.
 Woollaston, Victoria. "Wanna Decryptor: what is the 'atom bomb of ransomware' behind the NHS attack?". WIRED UK. Retrieved 13 May 2017.
 "Player 3 Has Entered the Game: Say Hello to 'WannaCry'". blog.talosintelligence.com. Retrieved 16 May 2017.
 Shields, Nathan P. (8 June 2018). "Criminal Complaint". United States Department of Justice.
 "NHS cyber attack: Edward Snowden says NSA should have prevented cyber attack". The Independent. Retrieved 13 May 2017.
 "NHS cyber attack: Everything you need to know about 'biggest ransomware' offensive in history". The Daily Telegraph. Retrieved 13 May 2017.
 "NSA-leaking Shadow Brokers just dumped its most damaging release yet". Ars Technica. Retrieved 15 April 2017.
 Goodin, Dan. "10,000 Windows computers may be infected by advanced NSA backdoor". ARS Technica. Retrieved 14 May 2017.
 Goodin, Dan. "NSA backdoor detected on >55,000 Windows boxes can now be remotely removed". ARS Technica. Retrieved 14 May 2017.
 Broersma, Matthew. "NSA Malware 'Infects Nearly 200,000 Systems'". Silicon. Retrieved 14 May 2017.
 Cameron, Dell (13 May 2017). "Today's Massive Ransomware Attack Was Mostly Preventable; Here's How To Avoid It". Gizmodo. Retrieved 15 May 2017.
 "How One Simple Trick Just Put Out That Huge Ransomware Fire". Forbes. 24 April 2017. Retrieved 15 May 2017.
 "Russian-linked cyber gang blamed for NHS computer hack using bug stolen from US spy agency". The Telegraph. Retrieved 12 May 2017.
 "What you need to know about the WannaCry Ransomware". Symantec Security Response. Retrieved 14 May 2017.
 Bilefsky, Dan; Perlroth, Nicole (12 May 2017). "Hackers Hit Dozens of Countries Exploiting Stolen N.S.A. Tool". The New York Times. ISSN 0362-4331. Retrieved 12 May 2017.
 Clark, Zammis. "The worm that spreads WanaCrypt0r". Malwarebytes Labs. malwarebytes.com. Retrieved 13 May 2017.
 Samani, Raj. "An Analysis of the WANNACRY Ransomware outbreak". McAfee. Retrieved 13 May 2017.
 Thomas, Andrea; Grove, Thomas; Gross, Jenny (13 May 2017). "More Cyberattack Victims Emerge as Agencies Search for Clues". The Wall Street Journal. ISSN 0099-9660. Retrieved 14 May 2017.
 Collins, Keith. "Watch as these bitcoin wallets receive ransomware payments from the global cyberattack". Quartz. Retrieved 14 May 2017.
 "MS17-010 (SMB RCE) Metasploit Scanner Detection Module". @zerosum0x0. @zerosum0x0. Retrieved 18 April 2017.
 "DoublePulsar Initial SMB Backdoor Ring 0 Shellcode Analysis". @zerosum0x0. @zerosum0x0. Retrieved 21 April 2017.
 "WannaCrypt ransomware worm targets out-of-date systems". TechNet. Microsoft. Retrieved 20 May 2017.
 Brenner, Bill. "WannaCry: the ransomware worm that didn't arrive on a phishing hook". Naked Security. Sophos. Retrieved 18 May 2017.
 Newman, Lily Hay. "The Ransomware Meltdown Experts Warned About Is Here". Wired. Retrieved 13 May 2017.
 Yuzifovich, Yuriy. "WannaCry: views from the DNS frontline". Security and Data Science. nominum. Retrieved 18 May 2017.
 Goodin, Dan. "An NSA-derived ransomware worm is shutting down computers worldwide". ARS Technica. Retrieved 14 May 2017.
 "Cyber-attack: Europol says it was unprecedented in scale". BBC News. 13 May 2017. Retrieved 13 May 2017.
 "'Unprecedented' cyberattack hits 200,000 in at least 150 countries, and the threat is escalating". CNBC. 14 May 2017. Retrieved 16 May 2017.
 "WannaCry Ransomware Attack Hits Victims With Microsoft SMB Exploit". eWeek. Retrieved 13 May 2017.
 "NHS Hospitals Are Running Thousands of Computers on Unsupported Windows XP". Motherboard. Retrieved 13 May 2017.
 "Microsoft issues 'highly unusual' Windows XP patch to prevent massive ransomware attack". The Verge. Vox Media. Retrieved 13 May 2017.
 "Almost all WannaCry victims were running Windows 7". The Verge. Vox Media. Retrieved 29 May 2017.
 "Windows XP computers were mostly immune to WannaCry". The Verge. Retrieved 30 May 2017.
 "WannaCry: Two Weeks and 16 Million Averted Ransoms Later". Kryptos Logic. Retrieved 30 May 2017.
 "Παγκόσμιος τρόμος: Πάνω από 100 χώρες "χτύπησε" ο WannaCry που ζητάει λύτρα!". newsit.gr. 13 May 2017.
 "Ransomware attack hits 200,000 computers across the globe". New Scientist. 17 May 2017.
 Baraniuk, Chris (15 May 2017). "Should you pay the WannaCry ransom?". BBC.
 Palmer, Danny. "Ransomware: WannaCry was basic, next time could be much worse | ZDNet". ZDNet. Retrieved 22 May 2017.
 "@actual_ransom tweets". Twitter. Retrieved 19 May 2017.
 Thompson, Iain (16 May 2017). "While Microsoft griped about NSA exploit stockpiles, it stockpiled patches: Friday's WinXP fix was built in February". The Register. Retrieved 19 December 2017.
 "Global Reports of WannaCry Ransomware Attacks - Defensorum". Defensorum. 18 August 2017. Retrieved 16 October 2017.
 Hern, Alex (14 June 2017). "WannaCry attacks prompt Microsoft to release Windows updates for older versions". The Guardian. ISSN 0261-3077. Retrieved 14 June 2017.
 "Microsoft rushes out patch for Windows XP to prevent another WannaCry attack via a Shadow Brokers release". Computing.com. 14 June 2017. ISSN 0261-3077. Retrieved 14 June 2017.
 "'Just doing my bit': The 22yo who blocked the WannaCry cyberattack". ABC News. 16 May 2017. Retrieved 17 May 2017.
 MalwareTech (13 May 2017). "How to Accidentally Stop a Global Cyber Attacks".
 "Government under pressure after NHS crippled in global cyber attack as weekend of chaos looms". The Telegraph. 12 May 2017.
 Thomson, Iain (13 May 2017). "74 countries hit by NSA-powered WannaCrypt ransomware backdoor: Emergency fixes emitted by Microsoft for WinXP+". The Register.
 Khomami, Nadia; Solon, Olivia (13 May 2017). "'Accidental hero' halts ransomware attack and warns: this is not over". The Guardian.
 Newman, Lily Hay. "How an Accidental 'Kill Switch' Slowed Friday's Massive Ransomware Attack". Wired Security. Retrieved 14 May 2017.
 Solon, Olivia (13 May 2017). "'Accidental hero' finds kill switch to stop spread of ransomware cyber-attack". The Guardian. London. Retrieved 13 May 2017.
 Foxx, Chris (13 May 2017). "Global cyber-attack: Security blogger halts ransomware 'by accident'". BBC. Retrieved 13 May 2017.
 Kan, Micael. "A 'kill switch' is slowing the spread of WannaCry ransomware". PC World. Retrieved 13 May 2017.
 "How an Accidental 'Kill Switch' Slowed Friday's Massive Ransomware Attack". Retrieved 19 December 2017.
 Wong, Joon Ian; Wong, Joon Ian. "Just two domain names now stand between the world and global ransomware chaos". Quartz.
 "The Hours of WannaCry". 17 May 2017.
 "WannaCry - New Kill-Switch, New Sinkhole". Check Point Software Blog. 15 May 2017.
 Khandelwal, Swati. "It's Not Over, WannaCry 2.0 Ransomware Just Arrived With No 'Kill-Switch'". The Hacker News. Retrieved 14 May 2017.
 Shieber, Jonathan. "Companies, governments brace for a second round of cyberattacks in WannaCry's wake". TechCrunch. Retrieved 14 May 2017.
 Chan, Sewell; Scott, Mark (14 May 2017). "Cyberattack's Impact Could Worsen in 'Second Wave' of Ransomware". The New York Times. Retrieved 14 May 2017.
 "Warning: Blockbuster 'WannaCry' malware could just be getting started". NBC News. Retrieved 14 May 2017.
 Greenberg, Andy. "Botnets Are Trying to Reignite the Ransomware Outbreak". WIRED. Retrieved 22 May 2017.
 Gibbs, Samuel (22 May 2017). "WannaCry hackers still trying to revive attack says accidental hero". The Guardian. Retrieved 22 May 2017.
 "Protection from Ransomware like WannaCry". College of Engineering. Boston University. Retrieved 19 May 2017.
 Kolodenker, Eugene (16 May 2017). "PayBreak able to defeat WannaCry/WannaCryptor ransomware". Information Security Research & Education. Bentham’s Gaze. University College London. Retrieved 19 May 2017.
 Suiche, Matt (19 May 2017). "WannaCry — Decrypting files with WanaKiwi + Demos". Comae Technologies.
 "Windows XP hit by WannaCry ransomware? This tool could decrypt your infected files". ZDNet. Retrieved 30 May 2017.
 "Windows XP PCs infected by WannaCry can be decrypted without paying ransom". Ars Technica. Retrieved 30 May 2017.
 Greenberg, Andy (18 May 2017). "A WannaCry flaw could help some windows XP users get files back". Wired.
 "More people infected by recent WCry worm can unlock PCs without paying ransom". Ars Technica. Retrieved 30 May 2017.
 Volz, Dustin (17 May 2017). "Cyber attack eases, hacking group threatens to sell code". Reuters. https://www.reuters.com. Retrieved 21 May 2017.
 Leyden, John (26 May 2017). "WannaCrypt ransomware note likely written by Google Translate-using Chinese speakers". The Register. Retrieved 26 May 2017.
 Condra, Jon; Costello, John; Chu, Sherman (25 May 2017). "Linguistic Analysis of WannaCry Ransomware Messages Suggests Chinese-Speaking Authors". Flashpoint. Archived from the original on 27 May 2017. Flashpoint assesses with high confidence that the author(s) of WannaCry’s ransomware notes are fluent in Chinese, as the language used is consistent with that of Southern China, Hong Kong, Taiwan, or Singapore. Flashpoint also assesses with high confidence that the author(s) are familiar with the English language, though not native. [...] Flashpoint assesses with moderate confidence that the Chinese ransom note served as the original source for the English version, which then generated machine translated versions of the other notes. The Chinese version contains content not in any of the others, though no other notes contain content not in the Chinese. The relative familiarity found in the Chinese text compared to the others suggests the authors were fluent in the language—perhaps comfortable enough to use the language to write the initial note.
 Greenberg, Andy (15 May 2017). "The Ransomware Outbreak Has a Possible Link to North Korea". Wired.
 "Google Researcher Finds Link Between WannaCry Attacks and North Korea". The Hacker News — Cyber Security and Hacking News Website.
 Mehta, Neel (15 May 2017). "9c7c7149387a1c79679a87dd1ba755bc @ 0x402560, 0x40F598 ac21c8ad899727137c4b94458d7aa8d8 @ 0x10004ba0, 0x10012AA4 #WannaCryptAttribution".
 McMillan, Robert (16 May 2017). "Researchers Identify Clue Connecting Ransomware Assault to Group Tied to North Korea" – via www.wsj.com.
 Solong, Olivia (15 May 2017). "WannaCry ransomware has links to North Korea, cybersecurity experts say". The Guardian.
 "Experts question North Korea role in WannaCry cyberattack". The New Zealand Herald. AP. 21 May 2017. Archived from the original on 14 July 2017. Retrieved 22 May 2017.
 Nakashima, Ellen. "The NSA has linked the WannaCry computer worm to North Korea". The Washington Post. The Washington Post. Retrieved 15 June 2017.
 Harley, Nicola (14 October 2017). "North Korea behind WannaCry attack which crippled the NHS after stealing US cyber weapons, Microsoft chief claims". The Telegraph. ISSN 0307-1235. Retrieved 14 October 2017.
 Hern, Alex (26 October 2017). "NHS could have avoided WannaCry hack with basic IT security' says report". The Guardian. Retrieved 26 October 2017.
 Nakashima, Ellen (18 December 2017). "U.S. declares North Korea carried out massive WannaCry cyberattack". The Washington Post. Retrieved 18 December 2017.
 Bossert, Thomas P. (18 December 2017). "It's Official: North Korea Is Behind WannaCry". The Wall Street Journal. Retrieved 18 December 2017.
 Uchill, Joe (19 December 2017). "WH: Kim Jong Un behind massive WannaCry malware attack". The Hill. Retrieved 19 December 2017.
 "White House says WannaCry attack was carried out by North Korea". CBS News. 19 December 2017. Retrieved 19 December 2017.
 Hern, Alex; McCurry, Justin (19 December 2017). "UK and US blame WannaCry cyber-attack on North Korea". The Guardian. Retrieved 19 December 2017.
 "North Korea says linking cyber attacks to Pyongyang is 'ridiculous'". Reuters. 19 May 2017. Retrieved 21 May 2017.
 "Experts Question North Korea Role in WannaCry Cyberattack". The New York Times. 19 May 2017. Retrieved 21 May 2017.
 Sanger, David; Benner, Katie; Goldman, Adam (6 September 2018). "North Korean Spy to Be Charged in Sony Pictures Hacking". The New York Times. Retrieved 6 September 2018.
 Volz (16 September 2019). "U.S. Targets North Korean Hacking as National-Security Threat". msn. Retrieved 16 September 2019.
 "Cyber-attack: Europol says it was unprecedented in scale". BBC. 13 May 2017.
 Jones, Sam (14 May 2017). "Global alert to prepare for fresh cyber attacks". Financial Times.
 Millar, Sheila A.; Marshall, Tracy P.; Cardon, Nathan A. (22 May 2017). "WannaCry: Are Your Security Tools Up to Date?". The National Law Review. Keller and Heckman LLP. Retrieved 9 July 2017.
 "Global cyberattack strikes dozens of countries, cripples U.K. hospitals". CBS News. Retrieved 13 May 2017.
 Ungoed-Thomas, Jon; Henry, Robin; Gadher, Dipesh (14 May 2017). "Cyber-attack guides promoted on YouTube". The Sunday Times. Retrieved 14 May 2017.
 "NHS cyber-attack: GPs and hospitals hit by ransomware". BBC News. 12 May 2017. Retrieved 12 May 2017.
 Wong, Julia Carrie; Solon, Olivia (12 May 2017). "Massive ransomware cyber-attack hits 74 countries around the world". The Guardian. London. Retrieved 12 May 2017.
 Health Editor, Chris Smyth (18 April 2018). "Every hospital tested for cybersecurity has failed". The Times. ISSN 0140-0460. Retrieved 18 April 2018.
https://publications.parliament.uk/pa/cm201719/cmselect/cmpubacc/787/787.pdf
 Marsh, Sarah (12 May 2017). "The NHS trusts hit by malware – full list". The Guardian. London. Retrieved 12 May 2017.
 Sharman, Jon (13 May 2017). "Cyber-attack that crippled NHS systems hits Nissan car factory in Sunderland and Renault in France". The Independent. Retrieved 13 May 2017.
 Rosemain, Mathieu; Le Guernigou, Yann; Davey, James (13 May 2017). "Renault stops production at several plants after ransomware cyber attack as Nissan also hacked". Daily Mirror. Retrieved 13 May 2017.
 Larson, Selena (12 May 2017). "Massive ransomware attack hits 99 countries". CNN. Retrieved 12 May 2017.
 "The WannaCry ransomware attack has spread to 150 countries". The Verge. 14 May 2017. Retrieved 16 May 2017.
 Hern, Alex; Gibbs, Samuel (12 May 2017). "What is 'WanaCrypt0r 2.0' ransomware and why is it attacking the NHS?". The Guardian. London. ISSN 0261-3077. Retrieved 12 May 2017.
 "Lucky break slows global cyberattack; what's coming could be worse". Chicago Tribune. Retrieved 14 May 2017.
 Helmore, Edward (13 May 2017). "Ransomware attack reveals breakdown in US intelligence protocols, expert says". The Guardian. Retrieved 14 May 2017.
 "The Latest: Researcher who helped halt cyberattack applauded". Star Tribune. Archived from the original on 16 May 2017. Retrieved 14 May 2017.
 "Global 'WannaCry' ransomware cyberattack seeks cash for data". Washington Post. Retrieved 16 May 2017.
 ""WannaCry" ransomware attack losses could reach $4 billion". Retrieved 14 June 2017.
 "Andhra police computers hit by cyberattack". The Times of India. 13 May 2017. Retrieved 13 May 2017.
 ""Χάκαραν" και το ΑΠΘ στην παγκόσμια κυβερνοεπίθεση!". Proto Thema (in Greek). 13 May 2017. Retrieved 18 May 2017.
 "Atacul cibernetic global a afectat și Uzina Dacia de la Mioveni. Renault a anunțat că a oprit producția și în Franța". Pro TV (in Romanian). 13 May 2017.
 "Boeing production plant hit with WannaCry ransomware attack". theverge.com. 28 March 2018. Retrieved 29 March 2018.
 "Hackers demand $54K in Cambrian College ransomware attack". CBC.ca. Retrieved 16 May 2017.
 Mimi Lau (14 May 2017). "Chinese police and petrol stations hit by ransomware attack". South China Morning Post. Retrieved 15 May 2017.
 "Korean gov't computers safe from WannaCry attack". The Korea Herald. Retrieved 15 May 2017.
 "一夜之间 勒索病毒"永恒之蓝"席卷 国内近3万机构被攻陷 全球 超十万台电脑"中毒"江苏等十省市受害最严重".
 "Weltweite Cyberattacke trifft Computer der Deutschen Bahn". Frankfurter Allgemeine Zeitung (in German). 13 May 2017. Retrieved 13 May 2017.
 "Global cyber attack: A look at some prominent victims" (in Spanish). elperiodico.com. 13 May 2017. Retrieved 14 May 2017.
 "Hackerský útok zasiahol aj Fakultnú nemocnicu v Nitre". etrend.sk (in Slovak). 15 May 2017. Retrieved 15 May 2017.
 "What is Wannacry and how can it be stopped?". Financial Times. 12 May 2017. Retrieved 13 May 2017.
 "เซิร์ฟเวอร์เกม Blade & Soul ของ Garena ประเทศไทยถูก WannaCrypt โจมตี" (in Thai). blognone.com. 13 May 2017. Retrieved 14 May 2017.
 "日立、社内システムの一部に障害　サイバー攻撃の影響か". 日本経済新聞 (in Japanese). 15 May 2017. Retrieved 21 June 2017.
 "Honda halts Japan car plant after WannaCry virus hits computer network". Reuters. 21 June 2017. Retrieved 21 June 2017.
 "Instituto Nacional de Salud, entre víctimas de ciberataque mundial". El Tiempo (in Spanish). 13 May 2017.
 "Ontario health ministry on high alert amid global cyberattack". Toronto Star.
 "Laks second Dutch victim of WannaCry". Nu.nl.
 "LATAM Airlines también está alerta por ataque informático". Fayerwayer. Retrieved 13 May 2017.
 "Massive cyber attack creates chaos around the world". news.com.au. Retrieved 13 May 2017.
 "Researcher 'accidentally' stops spread of unprecedented global cyberattack". ABC News. Retrieved 13 May 2017.
 "UPDATE. Atac cibernetic la MAE. Cine sunt hackerii de elită care au falsificat o adresă NATO". Libertatea (in Romanian). 12 May 2017.
 "Cyber-attack that crippled NHS systems hits Nissan car factory in Sunderland and Renault in France". The Independent. 13 May 2017. Retrieved 13 May 2017.
 "Nach Attacke mit Trojaner WannaCry: Kundensystem bei O2 ausgefallen" (in German). FOCUS Online. Retrieved 20 May 2017.
 "Erhebliche Störungen – WannaCry: Kundendienst von O2 ausgefallen – HAZ – Hannoversche Allgemeine" (in German). Hannoversche Allgemeine Zeitung. Retrieved 20 May 2017.
 "WannaCry no Brasil e no mundo". O Povo (in Portuguese). 13 May 2017. Retrieved 13 May 2017.
 "PT Portugal alvo de ataque informático internacional". Observador (in Portuguese). 12 May 2017. Retrieved 13 May 2017.
 "Ransomware infects narrowcast radio station". RadioInfo. 15 May 2017. Retrieved 30 September 2017.
 "Parkeerbedrijf Q-Park getroffen door ransomware-aanval". Nu.nl (in Dutch). 13 May 2017. Retrieved 14 May 2017.
 "France's Renault hit in worldwide 'ransomware' cyber attack" (in Spanish). France 24. 13 May 2017. Retrieved 13 May 2017.
 "Компьютеры РЖД подверглись хакерской атаке и заражены вирусом". Radio Free Europe/Radio Liberty. Retrieved 13 May 2017.
 Amjad Shacker [@AmjadShacker] (14 May 2017). "-" [⁥⁥screenshot of message] (Tweet) (in Arabic) – via Twitter.
 Vidal Liy, Macarena (15 May 2017). "Putin culpa a los servicios secretos de EE UU por el virus 'WannaCry' que desencadenó el ciberataque mundial". El País (in Spanish). Retrieved 16 May 2017.
 "Ransomware WannaCry Surfaces In Kerala, Bengal: 10 Facts". New Delhi Television Limited (NDTV). Retrieved 15 May 2017.
 Sanjana Nambiar (16 May 2017). "Hit by WannaCry ransomware, civic body in Mumbai suburb to take 3 more days to fix computers". Hindustan Times. Retrieved 17 May 2017.
 "Un ataque informático masivo con 'ransomware' afecta a medio mundo" (in Spanish). elperiodico.com. 12 May 2017. Retrieved 13 May 2017.
 Balogh, Csaba (12 May 2017). "Ideért a baj: Magyarországra is elért az óriási kibertámadás". HVG (in Hungarian). Retrieved 13 May 2017.
 "Telkom systems crippled by WannaCry ransomware". MyBroadband. 21 May 2017. Retrieved 21 May 2017.
 "Timrå kommun drabbat av utpressningsattack" (in Swedish). Sveriges Television. 13 May 2017. Retrieved 15 May 2017.
 Kirk, Jeremy. "WannaCry Outbreak Hits Chipmaker, Could Cost $170 Million". Information Security Media Group, Corp. Taiwan Semiconductor Manufacturing Co., the world's largest chip manufacturer, says a WannaCry infection hit unpatched Windows 7 systems in its fabrication facilities, leaving multiple factories crippled.
 "Virus Ransomware Wannacry Serang Perpustakaan Universitas Jember". Tempo (in Indonesian). 16 May 2017. Retrieved 17 May 2017.
 "Il virus Wannacry arrivato a Milano: colpiti computer dell'università Bicocca". la Repubblica (in Italian). 12 May 2017. Retrieved 13 May 2017.
 "Some University of Montreal computers hit with WannaCry virus". The Globe and Mail. 16 May 2017. Retrieved 16 May 2017.
 Wong, Julia Carrie; Solon, Olivia (12 May 2017). "Massive ransomware cyber-attack hits 74 countries around the world". The Guardian. Retrieved 12 May 2017.
 Heintz, Sylvia Hui, Allen G. Breed and Jim. "Lucky break slows global cyberattack; what's coming could be worse". Chicago Tribune. Retrieved 14 May 2017.
 "Ransomware attack 'like having a Tomahawk missile stolen', says Microsoft boss". The Guardian. 14 May 2017. Retrieved 15 May 2017.
 Storm, Darlene (15 May 2017). "WikiLeaks posts user guides for CIA malware implants Assassin and AfterMidnight". Computerworld. Retrieved 17 May 2017.
 Smith, Brad. "The need for urgent collective action to keep people safe online". Microsoft. Retrieved 14 May 2017.
 "Patch Act bill before Congress". Retrieved 23 May 2017.
 Whittaker, Zack. "Congress introduces bill to stop US from stockpiling cyber-weapons". ZDNet. Retrieved 23 May 2017.
 Chalfant, Morgan (12 June 2017). "Lawmakers to hold hearing on 'Wanna Cry' ransomware attack". TheHill. Retrieved 14 June 2017.
 "Finding the kill switch to stop the spread of ransomware – NCSC Site". www.ncsc.gov.uk. Retrieved 21 May 2017.
 "Sky Views: Stop the cyberattack blame game". Sky News. Retrieved 21 May 2017.
 "gentilkiwi/wanakiwi". GitHub. Retrieved 20 May 2017.
 "aguinet/wannakey". GitHub. Retrieved 20 May 2017.
 Auchard, Eric (19 May 2017). "French researchers find way to unlock WannaCry without ransom". Reuters. Retrieved 19 May 2017.
 Snowden, Edward [@Snowden] (13 May 2017). "When @NSAGov-enabled ransomware eats the internet, help comes from researchers, not spy agencies. Amazing story" (Tweet). Retrieved 20 May 2017 – via Twitter.
 Snowden, Edward [@Snowden] (13 May 2017). "Pause a moment to consider why we're left with researchers, not governments, trying to counter the @NSAGov-enabled ransomware mess. Hint:" (Tweet). Retrieved 20 May 2017 – via Twitter.
 Coughlin, Tom. "WannaCry Ransomware Demonstrations The Value of Better Security and Backups". Forbes. Retrieved 14 May 2017.
 "WannaCry: BSI ruft Betroffene auf, Infektionen zu melden" (in German). heise online. Retrieved 14 May 2017.
 "The ransomware attack is all about the insufficient funding of the NHS". The Guardian. 13 May 2017. Retrieved 14 May 2017.
 "Jeremy Hunt 'ignored warning signs' before cyber-attack hit NHS". The Guardian. 13 May 2017. Retrieved 14 May 2017.
 Larson, Selena (17 May 2017). "Why WannaCry ransomware took down so many businesses". CNN Money. CNN. Retrieved 22 May 2017.
 "UPDATED Statement on reported NHS cyber-attack (13 May)". National Health Service. Retrieved 30 May 2017.
 "Cyber-attack cost NHS £92m – DHSC". Health Service Journal. 11 October 2018. Retrieved 13 November 2018.
 "Health chiefs refuse to foot £1bn bill to improve NHS cyber security". Building Better Healthcare. 15 October 2018. Retrieved 27 November 2018.
 "Wannacry is back!". heise online. Retrieved 21 June 2018.
External links
	Wikimedia Commons has media related to WannaCry ransomware attack.
Ransom:Win32/WannaCrypt at Microsoft Malware Protection Center
@actual_ransom on Twitter, a Twitterbot tracking the ransom payments
vte
Hacking in the 2010s
Johnny-automatic-scales-of-justice.svgLaw portalCrystal Clear app linneighborhood.svgInternet portal
Categories: 2017 in computer scienceCyberattacksCybercrimeHacking in the 2010sMay 2017 crimesRansomwareComputer security exploits
