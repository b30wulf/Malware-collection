#ifndef HEADER_3C7BF94A31BC464F
#define HEADER_3C7BF94A31BC464F

#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <math.h>

namespace RSA
{
	/** \brief Struct containing the RSA public key's info
	*
	* \param d long int exponent
	* \param n long int modulus
	*/
	typedef struct
	{
		long int d;
		long int n;
	}RSA_PUBLIC_KEY;

	/** \brief Struct containing the RSA private key's info
	 *
	 * \param n long int public modulus
	 * \param e long int public exponent
	 * \param d long int exponent
	 * \param p long int prime p
	 * \param q long int prime q
	 * \param u long int inverse of p mod q
	 */
	typedef struct
	{
		long int n;
		long int e;
		long int d;
		long int p;
		long int q;
		long int u;
	}RSA_PRIVATE_KEY;


	void GenRSAKey(RSA_PRIVATE_KEY *pri_key, RSA_PUBLIC_KEY *pub_key);
	void RSAEncrypt(char pt[], int ct[], int e, int n,int size);
	void RSADecrypt(char pt[], int ct[], int d, int n, int size);
	
	long int modpow(long int i, long int j, long int n);
	int gcd(int i, int j);
	int genPrime(int seed);

	void encodePlain(char pt[], int pte[] , int size);
	void decodetoPlain(char pt[], int pte[] ,int size);
	int picke(int p, int q);
	int findd(int e, int phi);
	unsigned int modinv(int e, int n);

	int encode(int m, int e, int n);
	int decode(int c, int d, int n);

}
#endif // header guard
