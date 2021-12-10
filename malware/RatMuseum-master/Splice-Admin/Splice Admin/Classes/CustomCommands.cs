using System.Windows.Input;

namespace Splice_Admin.Classes
{
    public static class CustomCommands
    {
        public static readonly RoutedUICommand Enter = new RoutedUICommand(
            "Enter",
            "Enter",
            typeof(CustomCommands),
            new InputGestureCollection()
            {
                new KeyGesture(Key.Enter)
            });
    }
}
