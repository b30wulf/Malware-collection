#pragma once

class CShareRestrictedSD  
{
public:
	CShareRestrictedSD();
	virtual ~CShareRestrictedSD();
	SECURITY_ATTRIBUTES* GetSA();

protected:
	PACL					pAcl;
	SECURITY_ATTRIBUTES		sa;
	PSECURITY_DESCRIPTOR	pSD;
	
private:
	BOOL BuildRestrictedSD();
};

