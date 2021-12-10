namespace Slave.Core
{
    internal static class Config
    {
        #region Master connection settings
        /// <summary>
        /// IP to connect to
        /// </summary>
        public static string ip { get; set; } = "127.0.0.1";

        /// <summary>
        /// Port to connect to
        /// </summary>
        public static int port { get; set; } = 1111;

        /// <summary>
        /// Time in ms to wait between each master connection attempt
        /// </summary>
        public static int masterConnectionRetryDelay { get; } = 2000;

        /// <summary>
        /// Number of times the slave will try to connect to the master
        /// This is taken into account only if a botnet server address was specified
        /// </summary>
        public static int maxConnectionRetryCount { get; } = 20;
        #endregion Master connection settings

        #region Botnet settings

        /// <summary>
        /// Master botnet server adress, if you don't specify any,
        /// the slave will connect to the master with the specified ip and port
        /// </summary>
        public static string botnetAdress { get; set; } = null;

        /// <summary>
        /// Time in ms to wait for between each botnet request
        /// </summary>
        public static int botnetServerRequestRetryDelay { get; } = 5000;
        #endregion Botnet settings

        /// <summary>
        /// Indicates wether instance check should be done
        /// </summary>
        public static bool IgnoreMutex { get; set; } = false;

        /// <summary>
        /// If you want to show the slave window or not (for debug purposes)
        /// True : show window
        /// False : hide window
        /// </summary>
        public static bool debug { get; } = true;
    }
}
