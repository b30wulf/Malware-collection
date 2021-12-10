class Linter(object):
    def __init__(self):
        pass

    def prepend_stdlib(self, script):
        with open("data/stdlib.vbs", "rb") as f:
            stdlib = f.read().lower()

        return stdlib + script

    def minimize_glyph(self, script, glyph):
        orig_script = script
        script = script.replace(glyph + b" ", glyph)
        script = script.replace(b" " + glyph, glyph)
        if orig_script != script:
            return self.minimize_glyph(script,glyph)
        return script

    def minimize_script(self, script):
        if type(script) != bytes:
            script = script.encode()

        lines = []
        script = script.replace(b"\r", b"")
        script = self.minimize_glyph(script, b",")
        script = self.minimize_glyph(script, b"=")
        script = self.minimize_glyph(script, b"(")
        script = self.minimize_glyph(script, b")")
        script = self.minimize_glyph(script, b":")
        script = self.minimize_glyph(script, b"&")
        script = self.minimize_glyph(script, b"<")
        script = self.minimize_glyph(script, b">")

        for line in script.split(b"\n"):
            line = line.split(b"'")[0]
            line = line.strip()
            if line:
                lines.append(line)

        return b":".join(lines).lower()

    """
    def _remove_usage(self, line, stdlib, script, keyword):
        line = line.strip()
        start = keyword +  " "
        end = "end " + keyword
        if line.startswith(keyword + " "):
            name = line.split(start)[1].split("(")[0]

            if name not in script.lower():
                part1 = stdlib.split(line)[0]
                part2 = part1.split[1].split(end)

                stdlib = part1 + part2

        return stdlib
    """
